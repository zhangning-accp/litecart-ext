<?php
    define("FILE_TYPE",$_FILES['file']['type']);//上传的文件类型
    define("TMP_FILE",$_FILES['file']['tmp_name']);//临时文件路径
    define("TMP_DIR",dirname(TMP_FILE));// 临时存放文件的目录
    define("UNZIP_DIR",TMP_DIR . "/" .u_utils::guid()."/");// zip解压目录路径
    global $dataInfo;
    $dataInfo = array(
            "is_option_tree"=>false
    );
    set_time_limit(0);//设置脚本执行时间
    // import or export run.
    if (isset($_POST['import_products'])) {
        importCategoriesAndProducts();
    } elseif (isset($_POST['export_products'])) {
        //$csv_array = builderExportCSVArray();
        //exportCategoriesAndProducts();
    }

    /**
     * 完整导入分类和产品
     */
    function importCategoriesAndProducts()
    {
//        global $fileInfo;
        try {
            //$file_name = $_FILES['file']['name'];//上传的文件名
//            $file_type = $_FILES['file']['type'];//上传的文件类型
//            $tmp_file = $_FILES['file']['tmp_name'];//临时文件路径
//            $tmp_folder = dirname($tmp_file);// 得到临时存放文件的目录
            if (empty(TMP_FILE) || !is_uploaded_file(TMP_FILE)) {
                throw new Exception(language::translate('error_must_select_file_to_upload', 'You must select a file to upload'));
            }

            ob_clean();

            header('Content-Type: text/plain; charset=' . language::$selected['charset']);
            // 判断是不是csv文件，如果是直接读取，如果是zip解压后读取。
            if (FILE_TYPE === 'application/vnd.ms-excel') {
                $csv = file_get_contents(TMP_FILE);
                $csv = functions::csv_decode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset']);
                importProducts($csv);
            } else if (FILE_TYPE === 'application/x-zip-compressed') {
                //$un_zip_folder = u_utils::guid();// 生成临时解压目录
                //$un_zip_folder = $TMP_DIR . "/" . $un_zip_folder;//完整的解压目录
                //UN_ZIP_FOLDER = $un_zip_folder;
                if(!u_utils::exists(UNZIP_DIR)) {
                    u_utils::mkdirs(UNZIP_DIR);//创建解压目录
                }
                // 解压zip，到临时目录
                $is_unzip = u_utils::unZip(TMP_FILE, UNZIP_DIR);

                $files = u_utils::files(UNZIP_DIR);

                foreach ($files as $file) {
                    if(!u_utils::endWith("csv",$file)){
                        global $dataInfo;
                        $dataInfo['is_option_tree'] = true;
                        break;
                    }
                }

                if ($is_unzip === true) {//如果解压成功
                    // 1. 获取解压目录下的文件列表
                    $files = u_utils::files(UNZIP_DIR);
                    foreach ($files as $key => $file) {//遍历解压目录下的文件
                        if(u_utils::endWith("csv",$file)) {
                        $csv_head = array();// 导入的csv数据头。每次分片读取的数据都需要和头做整合。便于后期处理
                        $file = UNZIP_DIR . "/" . $file;// 拼接需要读取的csv文件文件路径
                        // 循环读取，每10w一次。
                        $rows = 200;//默认一次读取的数据行数
                        $csvFile = new csvreader($file);//创建csv文件对象
                        $csv_head = $csvFile->get_data(1, 0);//获取表头内容
                        $lineNumber = $csvFile->get_lines();//得到文件总行数
                        $loop = 1;// 循环次数
                        if ($lineNumber > $rows) {
                            if ($lineNumber / $rows == 0) {
                                $loop = $lineNumber / $rows;
                            } else {
                                $loop = ($lineNumber / $rows) + 1;
                            }
                        }
                        $loop = intval($loop);//处理为int型
                        for ($i = 0; $i < $loop; $i++) {//循环读取文件数据
                            $start = ($i * $rows) + 1;//开始位置
                            if (($start + $rows) > $lineNumber) {//如果最后的row大于剩下的行数，调整需要读取的行数，处理为有多少读多少。
                                $rows = $lineNumber - $start;
                            }
                            //trigger_error($i.' csv befor:'.var_dump(memory_get_usage()).'\r\n');
                            $csv = $csvFile->get_data($rows, $start);//获得指定位置和行数的数据
                            $csv = u_utils::disposalData($csv_head, $csv);//将数据和表头进行整合，成为key=>value
                            //trigger_error($i.' csv after and insert products befor :'.var_dump(memory_get_usage()).'\r\n');
                            importProducts($csv);//数据入库
                            // trigger_error($i.' insert products end :'.var_dump(memory_get_usage()).'\r\n');
                            unset($csv);//释放$csv
                            $csv = null;
                            //trigger_error($i.' unset csv:'.var_dump(memory_get_usage()).'\r\n');
                        }// loop read simple csv data file end ...
                    }// read csv file end ..
                }

                } else {
                    notices::add('errors', "Upload file failure");
                }
                // 删除临时解压目录下的所有数据
                u_utils::deleteDirectoryAndFile(UNZIP_DIR);
            }
            exit;
        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    /**
     * 抽取价格
     * @param $prices xx|xx
     * @return arry
     */
    function extractPrices($prices) {
        /*异常情况检测*/
        if(empty($prices)) {
            $prices = "0.00|0.00";
        }
        if(!u_utils::startWith("|",$prices) && !u_utils::endWith("|",$prices)) {
            $prices .= "|";
        }
        if(u_utils::startWith("|",$prices)) {
            $prices = "0.00".$prices;
        }
        if(u_utils::endWith("|",$prices)) {
            $prices.="0.00";
        }
        // 获取[0]原价和[1]销售价
        $prices = preg_split("/[|]/",$prices);
        return $prices;
    }
    // catalog->CSV Import/Export page and process import/export Categories or Products
    /**
     * 导入产品相关数据
     * @param $csv 产品csv数据
     *
     * csv关键数据的数据结构说明：
     *
     * [id]:导入时可以没有，但更新时必须有。
     * status:0|1|2.  0:下架，1：上架，2：隐藏
     * [code]:导入时程序生成 uuid
     * [sku]:导入时程序生成   uuid
     * name:* required.
     * short_description: * required.
     * description:* required.
     * attributes:* required.
     * head_title:* required.
     * meta_description:* required.
     * image:* required. 多个使用 ｜ 分隔
     * price:* required.
     * quantity:* required.
     * option_groups: option_name:option_value. 键值对，值只有一个，不支持多个。
     * product_groups: gn1:gv1,gv2,...|gn2:gv1,gv2,gv3  键值对，值允许多个，用逗号隔开。多个group格式为：
     *
     * categorie_names: c1|c2|c3|c4,c5,...
     * category_descriptions: d1[|]d2[|]|d3[,]d4[,]...
     * category_short_descriptions:同category_descriptions
     * category_meta_descriptions:同category_descriptions
     * category_h1_titles:同category_descriptions
     * category_head_titles:同category_descriptions
     */

    function importProducts(&$csv)
    {
        $product_map = array();

        foreach ($csv as $row) {//遍历csv文件
            $date = u_utils::getYMDHISDate();
            //构建商品所需数据
            $product_info = array(
                "id" => -1,
                "status" => 1,
                "manufacturer_id" => 0,
                "supplier_id" => 0,
                "delivery_status_id" => 1,
                "sold_out_status_id" => 1,
                "default_category_id" => 0,
                "product_groups" => "",
                "keywords" => "",
                "code" => "",
                "sku" => "",
                "mpn" => "",
                "upc" => "",
                "gtin" => "",
                "taric" => "",
                "quantity" => 0.00,
                "quantity_unit_id" => 1,
                "weight" => 0.00,
                "weight_class" => "kg",
                "dim_x" => 0.00,
                "dim_y" => 0.00,
                "dim_z" => 0.00,
                "dim_class" => "cm",
                "purchase_price" => 0.00,
                "purchase_price_currency_code" => "",
                "tax_class_id" => 0,
                "image" => "",
                "views" => 0,
                "purchases" => 0,
                "date_valid_from" => "1900-01-01",
                "date_valid_to" => "4900-01-01",
                "date_updated" => $date,
                "date_created" => $date,
                "name" => "",
                "short_description" => "",
                "description" => "",
                "attributes" => "",
                "head_title" => "",
                "meta_description" => "",
                "categorie_names" => "",
                "category_descriptions" => "",
                "category_short_descriptions" => "",
                "category_meta_descriptions" => "",
                "category_h1_titles" => "",
                "category_head_titles" => "",
                "option_groups" => "",
                "md5" => "",
                "price" => 0,
                "option_groups_array" =>array()
            );
            foreach ($row as $key => $value) {
                $product_info[$key] = addslashes(trim($value));// 对数据做转义处理
            }

            // -------------- 商品数据构建完毕 ---------------
            //$product_name = $product_info['name'];
            $product_code = $product_info['code'];
            if (!empty($product_code)) {
                // 价格处理
                $prices = $product_info['price'];
                $extr_prices = extractPrices($prices);
                $product_info['prices'] = $extr_prices;
                // 处理option group
                $option_groups_str = $product_info['option_groups'];
                $option_groups_array = u_utils::parseOptionGroup($option_groups_str);
                $product_info['option_groups_array'] = $option_groups_array;


                // 添加数据到数据库
                importProduct($product_info, $product_map);
                addCampaigns($product_info, $product_map);
                addImages($product_info, $product_map); //处理图片数据
                addOptionGroup($product_info);
                addProductGroup($product_info, $product_map);
                addCategories($product_info, $product_map);
                updateProductOther($product_info, $product_map);
                $product_map[$product_code] = true;
            }
        }
        // 跳转回导入界面，同时给出消息通知。
    }


    function importProduct(&$product_info, &$product_map)
    {
        //------------ 添加商品信息 -----------------
        $product_code = $product_info['code'];
        //1. 拆分image字符串$product_map
        $product_info['image'] = array_filter(preg_split("/\|/", $product_info['image']));
        $main_image = "";
        if (!empty($product_info['image'])) {
            $main_image = $product_info['image'][0];
        }
        // 以后这里逻辑要改为如果根据code查询，如果没有，则添加，如果有则修改
        if (!empty($product_code)) {
            $sql = "select id from " . DB_TABLE_PRODUCTS . " where code = '" . $product_code . "'";
            $result = database::fetch(database::query($sql));
            $product_id = $result['id'];
            $product_info['id'] = $product_id;
            if (empty($product_id)) {//新增
                // 新增，新增后，将product_id赋值给id
                $sql = "INSERT INTO " . DB_TABLE_PRODUCTS . " (status,manufacturer_id,supplier_id,delivery_status_id,
                      sold_out_status_id,default_category_id,product_groups,keywords,
                      code,sku,mpn,upc,gtin,taric,quantity,quantity_unit_id,weight,
                      weight_class,dim_x,dim_y,dim_z,dim_class,purchase_price,purchase_price_currency_code,
                      tax_class_id,image,views,purchases,date_valid_from,date_valid_to,date_updated,date_created)
                       values (%d,%d,%d,%d,
                              %d,%d,%d,'%s',
                              '%s',uuid(),'%s','%s','%s','%s',%.4f,%d,%d,
                              '%s',%d,%d,%d,'%s',%.4f,'%s',
                              %d,'%s',%d,%d,'%s','%s','%s','%s')";

                $parameter_values = array(
                    $product_info['status'], $product_info['manufacturer_id'], $product_info['supplier_id'], $product_info['delivery_status_id'],
                    $product_info['sold_out_status_id'], $product_info['default_category_id'], '', $product_info['keywords'],
                    $product_code, $product_info['mpn'], $product_info['upc'], $product_info['gtin'], $product_info['taric'],
                    0, $product_info['quantity_unit_id'], $product_info['weight'],
                    $product_info['weight_class'], $product_info['dim_x'], $product_info['dim_y'], $product_info['dim_z'],
                    $product_info['dim_class'], 0, $product_info['purchase_price_currency_code'],
                    $product_info['tax_class_id'], $main_image, $product_info['views'], $product_info['purchases'],
                    $product_info['date_valid_from'], $product_info['date_valid_to'], $product_info['date_updated'], $product_info['date_created']);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
                $product_id = database::insert_id();
                $product_info['id'] = $product_id;
                //2. 新增 product_info表数据
                $sql = "INSERT INTO %s (product_id,language_code,name,short_description,description,
                    head_title,meta_description,attributes)
                       values (%d,'%s','%s','%s','%s','%s','%s','%s')";

                $parameter_values = array(DB_TABLE_PRODUCTS_INFO,
                    $product_id, 'en', $product_info['name'], $product_info['short_description'],
                    $product_info['description'], $product_info['head_title'],
                    $product_info['meta_description'], $product_info['attributes']);

                $sql = u_utils::builderSQL($sql,$parameter_values);
                $result = database::query($sql);
            } else { // 更新
                //if (empty($product_map["'" . $md5_value . "'"])) {// 如果MD5值对应的value为空，表示是第一次更新。
                // 1. 对于product表，进行更新， 更新逻辑是当次任务里，如果没有找到$product_code对应的map数据，则更新，否则不会，
                //  保证了同批任务只会更新一次。在上百万的数据导入时减少数据库的操作。 测试通过
                if (!isset($product_map[$product_code])) { // 避免重复更新
                    $date = u_utils::getYMDHISDate();
                    $sql = "UPDATE " . DB_TABLE_PRODUCTS . "SET status = %d,quantity = %d,purchase_price = %.4f,image = '%s',date_updated = '%s' WHERE id = %d";
                    $parameter_values = array($product_info['status'], $product_info['quantity'],
                        $product_info['price'], $main_image, $date, $product_id);

                    $sql = u_utils::builderSQL($sql, $parameter_values);
                    database::query($sql);
                    // 更新product_info表
                    $sql = "UPDATE " . DB_TABLE_PRODUCTS_INFO . " SET name = '%s', short_description = '%s', description = '%s', head_title = '%s', 
                    meta_description = '%s', attributes = '%s' WHERE product_id = %d";

                    $parameter_values = array($product_info['name'], $product_info['short_description'],
                        $product_info['description'], $product_info['head_title'], $product_info['meta_description'],
                        $product_info['attributes'], $product_id);

                    $sql = u_utils::builderSQL($sql, $parameter_values);
                    $result = database::query($sql);
                }
            }// 以上代码测试通过 2018-09-18 14:50
        }
    }

    /**
     * 添加活动价格
     * @param $product_info
     * @param $product_map
     */
    function addCampaigns(&$product_info, &$product_map)
    {
        $product_code = $product_info['code'];
        if (!isset($product_map[$product_code])) {// 只有map里没有此数据时才进行更新处理
            $product_id = $product_info['id'];
            if (!empty($product_id)) {
                //如果价格是0或小于0表示没有特价，则删除这个。这里有个bug，这个bug是系统数据底层结构里特价只和商品本身挂钩，
                //所以没有办法做到同个商品多个规格的特价。需要修改表结构。
                if ($product_info['prices'][1] <= 0) {
                    $sql = "delete from " . DB_TABLE_PRODUCTS_CAMPAIGNS . " where product_id = " . $product_id;
                    $result = database::query($sql);
                    return;
                }
                $sql = "select count(1) as total from " . DB_TABLE_PRODUCTS_CAMPAIGNS . " where product_id = " . $product_id;
                $result = database::fetch(database::query($sql));
                if ($result['total'] < 1) {
                    $sql = "insert into " . DB_TABLE_PRODUCTS_CAMPAIGNS . " (product_id,start_date,end_date,USD,EUR) values (%d,'%s','%s',%d,%d)";
                    $parameter_values = array($product_id, '1900-01-01', "4900-01-01", $product_info['prices'][1], 0);
                    $sql = u_utils::builderSQL($sql, $parameter_values);
                    $result = database::query($sql);
                } else {
                    $sql = "update " . DB_TABLE_PRODUCTS_CAMPAIGNS . " set USD=%d where product_id =%d";
                    $parameter_values = array($product_id, $product_info['prices'][1]);
                    $sql = u_utils::builderSQL($sql, $parameter_values);
                    $result = database::query($sql);
                }
            }
        }
    }
    /**
     * 添加图片，测试多次无异常，放心用
     * @param $product_info 商品信息对象
     */
    function addImages(&$product_info, &$product_map)
    {
        //---------------------对于lc_products_images表，先删后增。这个不太合理，当有上百万的数据时，操作太平凡，同样采用product的方式---------------------
        $product_code = $product_info['code'];
        processProductLocalImage($product_info);
        if (!isset($product_map[$product_code])) {// 只有map里没有此数据时才进行更新处理
            $product_id = $product_info['id'];
            if (!empty($product_info['image']) && !empty($product_id)) {
                $sql = "delete from %s where product_id = %d";
                $parameter_values = array(DB_TABLE_PRODUCTS_IMAGES, $product_id);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
                foreach ($product_info['image'] as $image) {//添加新数据
                    $sql = "insert into %s (product_id,filename,checksum,priority) values(%d,'%s','%s',1)";
                    $parameter_values = array(DB_TABLE_PRODUCTS_IMAGES, $product_id, $image, "");
                    $sql = u_utils::builderSQL($sql, $parameter_values);
                    $result = database::query($sql);
                }
            }
        }
    }

    /**
     * 处理本地上传的图片，凡是不是以http开始的路径，我们都视为是本地图片，这些图片需要放到压缩包里上传到服务器
     * @param $product_info
     */
    function processProductLocalImage(&$product_info) {
        //$DEFAULT_FOLDER = "D:/zip-test";
        $TMP_FOLDER = UNZIP_DIR;// 最后的解压目录
        $WEB_SITE_FOLDER = FS_DIR_HTTP_ROOT.WS_DIR_IMAGES."products/";
        $WEB_SITE_COMMON_FOLDER = $WEB_SITE_FOLDER."common/";
        $product_code = $product_info['code'];
        $ws_links_dir = "products/".$product_code."/";
        $product_web_folder = $WEB_SITE_FOLDER.$product_code."/";
        if(!u_utils::exists($product_web_folder,false)) {// 如果网站目录下没有对应产品的图片目录，则创建该目录
            u_utils::mkdirs($product_web_folder);
        }
        $images = $product_info['image'];
        unset($product_info['image']);
        foreach ($images as $image => $link) {
            if(u_utils::startWith("http")) {
                $product_info['image'][] = $link;
            } else {
                if (!empty($link)) { //1. 判断是否有link
                    $tmp_link = UNZIP_DIR . $link;
                    $link_parent = dirname($link, 1);// 构建目录层级结构
                    $tmp_product_web_folder = $product_web_folder;
                    if ($link_parent !== "\\" && $link_parent !== "." && $link_parent !== "..") {
                        $tmp_product_web_folder = $tmp_product_web_folder . $link_parent . "/";
                    }
                    if (u_utils::exists($tmp_link)) {// 到解压目录下去找该文件，并将文件拷贝到网站的产品图片目录下
                        if (!u_utils::exists($tmp_product_web_folder, false)) {//目录不存在则创建
                            u_utils::mkdirs($tmp_product_web_folder);// 创建目录
                        }
                        if (!u_utils::exists($tmp_product_web_folder . basename($tmp_link))) {
                            // 如果网站目录下没有同名文件，则拷贝过去，这里可能会有一个问题，就是文件名相同，但内容不同。
                            $isCopy = copy($tmp_link, $tmp_product_web_folder . basename($tmp_link));
                        }
                        $product_info['image'][] = $ws_links_dir . $link;
                    } else {// 如果解压目录里没有找到，则到对应的网站产品图片目录下去找
                        if (u_utils::exists($tmp_product_web_folder . basename($tmp_link))) {
                            $product_info['image'][] = $ws_links_dir . $link;
                        }
                    }
                }
            }
        }
    }
    /**
     * 处理资源情况
     * @param $product_info
     */
    function processSource(&$product_info) {
        //$DEFAULT_FOLDER = "D:/zip-test";
        $TMP_FOLDER = UNZIP_DIR;// 最后的解压目录
        $WEB_SITE_FOLDER = FS_DIR_HTTP_ROOT.WS_DIR_IMAGES."products/";
        $WEB_SITE_COMMON_FOLDER = $WEB_SITE_FOLDER."common/";
        // 检查解压目录下是否还有别的文件，如果有，默认认为是T-shirts 数据
        $product_code = $product_info['code'];
        $ws_links_dir = "products/".$product_code."/";
        $product_web_folder = $WEB_SITE_FOLDER.$product_code."/";
        if(!u_utils::exists($product_web_folder,false)) {// 如果网站目录下没有对应产品的图片目录，则创建该目录
            u_utils::mkdirs($product_web_folder);
        }
        $option_group_str = $product_info['option_groups'];
        $option_group_array = u_utils::parseOptionGroup($option_group_str);
        $product_info["option_groups_array"] = $option_group_array;
        foreach ($option_group_array as $option_group_name => $values) {
            foreach($values as $value=>$link) {
                //TODO: 这里还应该判断是不是http开头，如果是则
                if(u_utils::startWith("http")) {
                    $link = str_replace("@",":");//把@符号替换成 冒号
                    $product_info["option_groups_array"][$option_group_name][$value] = $link;
                    continue;
                }
                if(!empty($link)) { //1. 判断是否有links
//                  是否是common开头
                    $isCommon = u_utils::startWith("common",$link);
                    if($isCommon) {
//                        echo $link."是common文件";
                        $tmp_link = UNZIP_DIR.$link;
                        if(u_utils::exists($tmp_link)) {// 检查解压目录里是否有该文件
                            // 存在，复制到网站common目录
                            if(!u_utils::exists($WEB_SITE_COMMON_FOLDER,false)) {
                                u_utils::mkdirs($WEB_SITE_COMMON_FOLDER);
                            }
                            // 拷贝之前应该检查目标目录是否有该文件，如果有，则不进行io操作。
                            if(!u_utils::exists($WEB_SITE_COMMON_FOLDER . basename($link))) {
                                $isCopy = copy($tmp_link, $WEB_SITE_COMMON_FOLDER . basename($link));
                            }
                            // 这里的link要修改路径，因为拷贝过去后，就是系统里的link了。products/{code}/{link}格式
                            $product_info["option_groups_array"][$option_group_name][$value] = "products/".$link;
//                            echo $tmp_link."存在解压目录common里</br>";
                        } else {// 解压目录没有此图片，到网站默认的common路径去找是否存在此图片
                            $link = $WEB_SITE_FOLDER.$link;
                            if(u_utils::exists($link)) {
//                                echo $link."存在系统common里</br>";
                                // 存在
                                $product_info["option_groups_array"][$option_group_name][$value] = "products/".$link;
                            } else {
                                //不处理
//                                echo $link."不存在在系统common里</br>";
                            }
                        }
                    } else {// 非common资源
//                        echo $link."是非common资源</br>";
//                        $unzip_folder = $product_folder;
                        $tmp_link = UNZIP_DIR.$link;
//                        $web_site_product = $WEB_SITE_FOLDER.$product_code."/";
                        $link_parent = dirname($link,1);// 构建目录层级结构
                        $tmp_product_web_folder = $product_web_folder;
                        if($link_parent !== "\\" && $link_parent !== "." && $link_parent !== ".." ) {
                            $tmp_product_web_folder = $tmp_product_web_folder.$link_parent."/";
                        }

                        // 到解压目录下去找该文件
                        if(u_utils::exists($tmp_link)) {
                            // 拷贝到网站目录下。目录名是 product_code.
                            if(!u_utils::exists($tmp_product_web_folder,false)) {
                                u_utils::mkdirs($tmp_product_web_folder);// 创建目录
                            }
                            if(!u_utils::exists($tmp_product_web_folder.basename($tmp_link))) {
                                $isCopy = copy($tmp_link,$tmp_product_web_folder.basename($tmp_link));
                            }
                            $product_info["option_groups_array"][$option_group_name][$value] = $ws_links_dir.$link;
//                            echo $link."解压目录里存在，并拷贝到".$tmp_product_web_folder.basename($tmp_link)."/</br>";
                        } else {
                            // 不处理 如果没找到，到对应的网站目录下去找，
                            if(u_utils::exists($tmp_product_web_folder.basename($tmp_link))) {
                                $product_info["option_groups_array"][$option_group_name][$value] = $ws_links_dir.$link;
//                                echo $link."解压目录里不存在，在".$tmp_product_web_folder."里找到了</br>";
                            } else {
//                                echo $link."解压目录里不存在，".$WEB_SITE_FOLDER.$product_code." 里也没有</br>";
                            }
                        }
                    }
                } else {//没有links 第一版不处理没有links的情况
//                    echo "links is null</br>";
                }
            }
        }
//        var_dump($product_info);
    }
    /**
     * @param $product_info
     */
    function addOptionGroup(&$product_info)
    {
        // 处理资源并修改$product_info信息
        processSource($product_info);

        // 这个每次都要检测并更新或添加。这里添加的时规格。这里有个问题，实际上一个产品可能具有多个规则。所以要支持多规格。这里只支持单规格。
        //option_groups
        //  数据格式:   style:S,M,XL,SL,5-links：1,2,3,4,5|size:x,xl-links：|color:dddf-links
        // links： option
        /*
         * {
                "Style": {
                    "Classic": "products\/common\/Classic.jpg"
                },
                "Color": {
                    "#FFF": "products\/48da8abf437a492eb24d4b3d2121ea49\/Classic\/WhiteTshirt.png",
                    "#efce1f": "products\/48da8abf437a492eb24d4b3d2121ea49\/Classic\/DaisyTshirt.png",
                    "#d5ab47": "products\/48da8abf437a492eb24d4b3d2121ea49\/Classic\/GoldTshirt.png",
                    "#50af72": "products\/48da8abf437a492eb24d4b3d2121ea49\/Classic\/IrishGreenTshirt.png"
                },
                "Size": {
                    "X SM (Youth)": "","SM (Youth)": "","MED (Youth)": "",
                    "SM": "","MED": "","LG": "","XL": "","2XL": "",
                    "3XL": "","4XL": "","5XL": "","6XL": ""
                }
            }
         这是一个只有一层的树结构。最顶层的是就是第一个元素，其它的元素都是兄弟关系
         * */
        $option_groups_array = $product_info['option_groups_array'];
//        $option_groups_array = u_utils::parseOptionGroup($option_groups_str);
//        if (!empty($option_groups_str)) {

        if(!empty($option_groups_array)) {
            reset($option_groups_array);
            $rootOptinName = key($option_groups_array);// 拿到 Style
            $rootOptionValue = key($option_groups_array[$rootOptinName]); // 拿到Style
            foreach ($option_groups_array as $option_name=>$option_values) {
                $option_values = $option_groups_array[$option_name];
                foreach ($option_values as $option_value=>$link_value) {
                    addSimpleOptionGroup($option_name, $option_value,$link_value,$rootOptinName,$rootOptionValue,$product_info);
                }
            }
        }
    }


    /**
     * 添加单个的option groups
     * @param string $gourp_name 实际就是option_name
     * @param string $option_name 实际就是option_value
     * @param $link_value 当前规格对应的link值
     * @param $product_info
     */
    function addSimpleOptionGroup($group_name='',$option_name='',$link_value="",$parentName,$parentValue,&$product_info)
    {

//        $option_groups = preg_split("/:/", $option_groups_str);
//        $group_name = $option_groups[0];
//        $option_name = $option_groups[1];
        // 查找是否存在该数据选项，如果不存在，则添加，存在则不做处理
        //1. 需要查找lc_option_groups_info和lc_option_values_info。
        //类型是textarea和input的选项数据是放在lc_option_values表里。暂时不考虑这两种类型
        // 其它类型的选项数据是放在lc_option_values_info表里
        $product_id = $product_info['id'];
        $date = u_utils::getYMDHISDate();
        $combination = "";
        $value_id = "";
        // 查找group_name 是否在 option_groups_info 表里，如果不存在就添加。
        $sql = "select id,group_id from " . DB_TABLE_OPTION_GROUPS_INFO . " where name = '%s'";
        $parameter_values = array($group_name);
        $sql = u_utils::builderSQL($sql, $parameter_values);
        $result = database::fetch(database::query($sql));
        $group_id = $result['group_id'];
        if (empty($result)) {
            // 添加一个group_name 需要在两张表里添加数据，`lc_option_groups` 和 lc_option_groups_info`.
            //添加 如下数据到对应表：
            //1. `lc_option_groups`
            $sql = "insert into " . DB_TABLE_OPTION_GROUPS . " (function,required,sort,date_created) VALUES ('%s',1,'%s','%s')";
            $parameter_values = array('select', 'priority', $date);

            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::query($sql);//拿到id值
            $group_id = database::insert_id();
            //`lc_option_groups_info`
            $sql = "insert into %s (group_id,language_code,name,description) VALUES (%d,'en','%s','')";
            $parameter_values = array(DB_TABLE_OPTION_GROUPS_INFO, $group_id, $group_name);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::query($sql);
            // 添加$option_name 数据结束
        }

        // 先查询$option_value在表lc_option_values_info是否存在，如果不存在，则添加，如果存在则忽略
        /*
         * SELECT `litecart`.`lc_option_values`.id as value_id,`litecart`.`lc_option_values`.group_id
         * FROM `litecart`.`lc_option_values_info` INNER JOIN `litecart`.`lc_option_values`
         *  ON value_id = `litecart`.`lc_option_values`.id
            AND `litecart`.`lc_option_values`.group_id = 115 AND `litecart`.`lc_option_values_info`.name = 'M'
         */
        //查找$option_value是否存在，查询时涉及两张表lc_option_values和lc_option_values_info。只有在info表里才有具体的值。
        $sql = "SELECT %s.id as value_id,%s.group_id FROM %s INNER JOIN %s ON value_id = %s.id 
                AND %s.group_id = %d AND %s.name = '%s'";
        $parameter_values = array(
            DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES,
            DB_TABLE_OPTION_VALUES_INFO, DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES,
            $group_id, DB_TABLE_OPTION_VALUES_INFO, $option_name);
        $sql = u_utils::builderSQL($sql, $parameter_values);
        $result = database::fetch(database::query($sql));
        if (empty($result)) {//如果$option_value不存在。则添加，如果存在则更新。
            // 添加 option_values 也涉及两张表lc_option_values和lc_option_values_info
            $sql = "insert into " . DB_TABLE_OPTION_VALUES . " (group_id,value,priority) VALUES (%d,'',1)";
            $parameter_values = array($group_id);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::query($sql);
            $value_id = database::insert_id();
            //添加到`lc_option_values_info`
            $sql = "insert into " . DB_TABLE_OPTION_VALUES_INFO . " (value_id,language_code,name) VALUES (%d,'en','%s')";
            $parameter_values = array($value_id, $option_name);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::query($sql);
            // --------- value_info 添加结束 -------------------
            // ---------------------------以上操作测试无误。 ---------------------------
        } else {//存在 选项值
            // 如果已经存在 option_value,则赋值value_id.
            $value_id = $result['value_id'];
        }
        // ------------ 关联商品操作 lc_products_options这样页面上购买商品是才有可选项。 ---------
        //1. 查找在product_optioins 表里有没有product_id,group_id,value_id 一一对应的记录，如果没有，则需要添加
        $sql = "SELECT product_id FROM " . DB_TABLE_PRODUCTS_OPTIONS . " 
                    WHERE product_id=%d AND group_id=%d AND value_id=%d";
        $parameter_values = array($product_id, $group_id, $value_id);

        $sql = u_utils::builderSQL($sql, $parameter_values);
        $result = database::fetch(database::query($sql));
        if (empty($result)) {// 导入是应该不会存在更新问题，所以此处忽略 // TODO:操作上有个潜在的逻辑问题，如果是更新了option数据，那么怎么办？比如说links或exsitesion
            $sql = "insert into" . DB_TABLE_PRODUCTS_OPTIONS . " (product_id,group_id,value_id,price_operator,USD,EUR,
                                        priority,links,date_updated,date_created) 
                        VALUES (%d,%d,%d,'+',0.000,0.000,1,'%s','%s','%s')";
            $parameter_values = array($product_id, $group_id, $value_id, $link_value, $date, $date);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::query($sql);
        } else {// 做更新操作，主要是更新links
            $sql = "update " . DB_TABLE_PRODUCTS_OPTIONS . " set links='%s',date_updated='%s' 
                    where product_id=%d and group_id=%d and value_id=%d";
            $parameter_values = array($link_value,$date,$product_id, $group_id, $value_id);
            $sql = u_utils::builderSQL($sql,$parameter_values);
            $result = database::query($sql);

        }
        // ---------------------------- 处理库存  这里有数据问题 ------------------------------------------------
        $combination = $group_id . "-" . $value_id;
        // 关联 lc_products_options_stock，首先检查是否有对应的stock记录，如果有，则更新，如果没有则添加
        $sql = "SELECT product_id,combination FROM " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " WHERE product_id=%d AND combination ='%s'";
        $parameter_values = array($product_id, $combination);
        $sql = u_utils::builderSQL($sql, $parameter_values);
        $result = database::fetch_full(database::query($sql));
        if (empty($result)) {
            $sql = "INSERT INTO " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " (product_id,combination,sku,weight,
                                                weight_class,dim_x,dim_y,dim_z,dim_class,quantity,
                                                priority,date_updated,date_created)
                                        VALUES(%d,'%s',UUID(),%d,'%s',
                                              '%s',%.4f,%.4f,%.4f,'%s',%d,'%s','%s')";
            $parameter_values = array($product_id, $combination, $product_info['weight'],
                $product_info['weight_class'], $product_info['dim_x'], $product_info['dim_y'], $product_info['dim_z'], $product_info['dim_class'], $product_info['quantity'],
                1, $date, $date);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            database::query($sql);
        } else {
            $current_quantity = $product_info['quantity'];
            // 更新库存
            $sql = "update " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " set quantity = %.4f,date_updated='%s' 
                                                                        where product_id=%d and combination='%s'";

            $parameter_values = array($current_quantity, $date, $product_id, $combination);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            database::query($sql);
        }
        // 统计总数，统一更新库存
        $sql = "select sum(quantity) as quantity from " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " where product_id = " . $product_id;
        $result = database::fetch(database::query($sql));
        // 计算差值
        $quantity = $result['quantity'];
        $sql = "update " . DB_TABLE_PRODUCTS . " set quantity = quantity + " . $quantity . " where id=" . $product_id;
        $result = database::query($sql);

        //----------------------  添加 T-shirts 到 表 product_option_trees 里
        global $dataInfo;
        $isOptionTree = $dataInfo['is_option_tree'];
        if ($isOptionTree === true) {
            // 另外一种添加方式，需要将数据添加到 product_option_trees 表
            //1. product_id group_id value_id parent_id parent_group_value_id links.
//            $product_id,$value_id,$group_id,$link_value. 怎么获取到parent_id.
            // 根据product_id,$value_id,$group_id和$parentName得到 父亲的id。如果parentName和当前传入的grop_name 一样，则id为0
            if($group_name !== $parentName && !empty($parentName)) {
                //1. 根据product_id,$value_id,$group_id和$parentName得到 父亲的id。如果parentName和当前传入的grop_name 一样，则id为0
                $sql = "select group_id from " . DB_TABLE_OPTION_GROUPS_INFO . " where name = '" . $parentName . "' limit 1";
                $result = database::fetch(database::query($sql));
                $parentId = $result['group_id'];
                //2. 根据parent_group_id 和 parent_group_value  拿到 对应的parent_value_id.
                $sql = "SELECT ov.id FROM ".DB_TABLE_OPTION_VALUES." AS ov INNER JOIN ".DB_TABLE_OPTION_VALUES_INFO.
                                        " AS ovi  ON ovi.value_id = ov.id AND ovi.name = '%s' AND ov.group_id  = %d";
                $parameter_values = array($parentValue,$parentId);
                $sql = u_utils::builderSQL($sql,$parameter_values);
                $result = database::fetch(database::query($sql));
                $parentValueId = $result['id'];
            } else {
                $parentId = 0;
                $parentValueId = 0;
            }
            $date = u_utils::getYMDHISDate();
            // update
            $sql = "select count(1) as total from ".DB_TABLE_PRODUCT_OPTION_TREES.
                " where product_id=%d and group_id=%d and value_id=%d and parent_group_id=%d and parent_value_id=%d";
            $parameter_values = array($product_id,$group_id,$value_id,$parentId, $parentValueId);
            $sql = u_utils::builderSQL($sql,$parameter_values);
            $result = database::fetch(database::query($sql));
            if($result['total'] > 0){
                $date = u_utils::getYMDHISDate();
                $sql = "update ".DB_TABLE_PRODUCT_OPTION_TREES." set links='%s', date_update='%s'
                    where product_id=%d and group_id=%d and value_id=%d and parent_group_id=%d and parent_value_id=%d";
                $parameter_values = array($link_value,$date,$product_id,$group_id,$value_id,$parentId,$parentValueId);
                $sql = u_utils::builderSQL($sql,$parameter_values);
                $result = database::query($sql);
            } else {
                // Insert 插入之前还是应该判断一下是否存在相同的id数据，如果存在则更新，否则会有很多重复数据
                $sql = "insert into " . DB_TABLE_PRODUCT_OPTION_TREES . "(`product_id`,`group_id`,`value_id`,
                `parent_group_id`, `parent_value_id`,`links`, `date_update`, `date_created` ) VALUES (
                %d,%d,%d,%d,%d,'%s','%s','%s');";
                $parameter_values = array($product_id,$group_id,$value_id,$parentId,$parentValueId,$link_value,$date, $date);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
            }
        }
    }
    /**
     * @param $product_info
     */
    function addProductGroup(&$product_info, &$product_map)
    {
        // 这里的group实际上就是客户端左边的筛选条件。有了这个，用户可以直接点击条件进行筛选。所以按照正常情况，一个产品的多种规格都应该同属一个product
        //$group_name_str数据格式：gropu_name:child_1,child_2,child_n|gropu_name:child_1,child_2,child_n。如果 $group_name 为空，则不做处理
        $product_code = $product_info['code'];
        if (!isset($product_map[$product_code])) {// 只在第一次，
            $product_groups_str = trim($product_info['product_groups']);
            $product_groups = "";
            if (!empty($product_groups_str)) {
                // 添加product_groups 相关数据操作
                //1.在lc_product_groups_info表里查找是否存在，
                $multiple_groups = preg_split("/\|/", $product_groups_str);//获取多个产品组数据
                $multiple_groups = array_filter($multiple_groups);//过滤掉空元素。因为可能会有xxx|  这样的情况
                foreach ($multiple_groups as $simple_groups) {
                    $simple_groups = preg_split("/:/", $simple_groups);
                    $group_name = $simple_groups[0];
                    $group_values = $simple_groups[1];
                    $group_values = preg_split("/,/", $group_values);
                    $group_values = array_filter($group_values);
                    //查找$group_name 在lc_product_groups_info表里是否存在
                    $sql = "select id,product_group_id from %s where name ='%s' limit 1";
                    $parameter_values = array(DB_TABLE_PRODUCT_GROUPS_INFO, $group_name);
                    $sql = u_utils::builderSQL($sql, $parameter_values);
                    $result = database::fetch(database::query($sql));
                    $product_group_id = $result['product_group_id'];
                    if (empty($result)) {// group_name数据不存在
                        //添加$group_name 到lc_product_groups表
                        $sql = "insert into %s (status,date_updated,date_created) values(1,'%s','%s')";
                        $date = u_utils::getYMDHISDate();
                        $parameter_values = array(DB_TABLE_PRODUCT_GROUPS, $date, $date);
                        $sql = u_utils::builderSQL($sql, $parameter_values);
                        $result = database::query($sql);
                        $product_group_id = database::insert_id();
                        // 添加$group_name到lc_product_groups_info
                        $sql = "insert into %s (product_group_id,language_code,name) values(%d,'en','%s')";
                        $parameter_values = array(DB_TABLE_PRODUCT_GROUPS_INFO, $product_group_id, $group_name);

                        $sql = u_utils::builderSQL($sql, $parameter_values);
                        $result = database::query($sql);
                    }
                    //2. 查找子项是否存在，如果不存在则添加. // 改为全部查，然后做对比。
                    $sql = "SELECT product_group_value_id,`name` FROM ".DB_TABLE_PRODUCT_GROUPS_VALUES_INFO." WHERE NAME IN(";
                    $in = "";
                    $in = "'" . JOIN("','", array_values($group_values)) . "'";
                    $in .= ")";
                    $sql .= $in." AND product_group_value_id IN(SELECT product_group_value_id FROM ".DB_TABLE_PRODUCT_GROUPS_VALUES_INFO .
                        " INNER JOIN ". DB_TABLE_PRODUCT_GROUPS_VALUES." as dpgv ON product_group_value_id = dpgv.id AND product_group_id = ". $product_group_id . ")";
//                $result = database::query($sql);
//                $result = database::fetch_full($result);

//                    $sql = "SELECT product_group_value_id,`name` FROM " . DB_TABLE_PRODUCT_GROUPS_VALUES_INFO . "
//                INNER JOIN " . DB_TABLE_PRODUCT_GROUPS_VALUES . "
//	            ON product_group_value_id = " . DB_TABLE_PRODUCT_GROUPS_VALUES . ".id AND product_group_id = " . $product_group_id;
                    $result = database::fetch_full(database::query($sql));
                    $group_value_ids = array();//$result['product_group_value_id'];
                    $value_names = array();//$result['name'];
                    foreach ($result as $row) {
                        $group_value_ids [] = $row['product_group_value_id'];
                        $value_names [] = addslashes($row['name']);
                    }
                    // 找到 $group_values有 但$value_names没有的数据
                    $diff = array_diff($group_values, $value_names);//寻找差值，如果result里没有，则添加
                    $diff = array_filter($diff);//把空元素过滤掉
                    if (!empty($diff)) {// 如果找到数据库没有的product group
                        //添加group子项到lc_product_groups_values
                        foreach ($diff as $value_name) {
                            $date = u_utils::getYMDHISDate();
                            $sql = "insert into ".DB_TABLE_PRODUCT_GROUPS_VALUES." (product_group_id,date_updated,date_created) values(%d,'%s','%s')";
                            $parameter_values = array($product_group_id, $date, $date);
                            $sql = u_utils::builderSQL($sql,$parameter_values);
                            $result = database::query($sql);
                            $group_value_id = database::insert_id();
                            // 添加group子项lc_product_groups_values_info
                            $sql = "insert into ".DB_TABLE_PRODUCT_GROUPS_VALUES_INFO." (`product_group_value_id`,`language_code`,`name`) values(%d,'en','%s')";
                            $parameter_values = array($group_value_id, $value_name);
                            $sql = u_utils::builderSQL($sql,$parameter_values );
                            $result = database::query($sql);
                            $group_value_ids[] = $group_value_id;
                        } // 以上代码简单测试通过 2018-09-17
                    }
                    foreach ($group_value_ids as $value_id) {
                        $product_groups .= $product_group_id . "-" . $value_id . ",";
                    }
                    /**--------------添加product_groups 相关数据操作结束 ------------**/
                    //去掉最尾部多余的逗号，否则客户页显示会有错误提示，影响观感,但是不能在这里去除，因为如果有多个group,这里就会导致类似这样的格式：
                    // 60-142,60-141,60-140,60-139,60-13861-143,61-144.
                    // 注意60-13861-143实际上应该是60-138,61-143，把去除逗号移到updateProductOther里。
                    //--------------------------------------------------
                    //$product_groups = substr($product_groups, 0, -1);
                    $product_info['product_groups'] = $product_groups;//重设product_groups内容
                }
            }
        }
    }

    //-------------   category -----------------------------
    /**
     * 导入分类
     * @param $product_info
     * @param $isInsertNew 当数据不存在时是否插入新数据。如果true，当数据库找不到导入的数据时，则将新增数据到数据库.
     * 该方法测试通过。 2018-9-16 11:30
     */
    function addCategories(&$product_info, &$product_map)
    {
        $product_code = $product_info['code'];
        if (!isset($product_map[$product_code])) {// 只在第一次，
            $categorie_names = $product_info['categorie_names'];//"Rubber Ducks|Subcategory|shot,d,d,d";
            if (!empty($categorie_names)) {//如果name不为空，则进行处理，否则忽略
                $category_descriptions = $product_info['category_descriptions'];// [|]ddddd[|]|[|]dssdfdf[|], 注：如果某些分类没有title，则用一个空格 | | | , ,
                $category_short_descriptions = $product_info['category_short_descriptions'];
                $category_meta_descriptions = $product_info['category_meta_descriptions'];
                $category_head_titles = $product_info['category_head_titles'];
                $category_h1_titles = $product_info['category_h1_titles'];

                //示例： ["Collectibles","Barware","Shot Glasses,man,woman"]
                $categorie_names = preg_split("/[|]/", $categorie_names);
                //["Collectibles","Barware","Shot Glasses,man,woman"]
                $categorie_names = array_filter($categorie_names);
                //"Shot Glasses,man,woman"
                $category_name_other_level_one = array_pop($categorie_names);
                //["Shot Glasses","man","woman"]
                $category_name_other_level_one = findFirstCategory($category_name_other_level_one, "/,/");
                //将第一个分类放到原数组。["Collectibles","Barware","Shot Glasses"]
                $categorie_names[] = array_shift($category_name_other_level_one);

                $category_descriptions = preg_split("/\[\|\]/", $category_descriptions);
                $category_descriptions = array_filter($category_descriptions);
                $category_descriptions_other_level_one = array_pop($category_descriptions);
                $category_descriptions_other_level_one = findFirstCategory($category_descriptions_other_level_one, "/\[,\]/");
                $category_descriptions[] = array_shift($category_descriptions_other_level_one);

                $category_short_descriptions = preg_split("/\[\|\]/", $category_short_descriptions);
                $category_short_descriptions = array_filter($category_short_descriptions);
                $category_short_descriptions_other_level_one = array_pop($category_short_descriptions);
                $category_short_descriptions_other_level_one = findFirstCategory($category_short_descriptions_other_level_one, "/\[,\]/");
                $category_short_descriptions[] = array_shift($category_short_descriptions_other_level_one);

                $category_meta_descriptions = preg_split("/\[\|\]/", $category_meta_descriptions);
                $category_meta_descriptions = array_filter($category_meta_descriptions);
                $category_meta_descriptions_other_level_one = array_pop($category_meta_descriptions);
                $category_meta_descriptions_other_level_one = findFirstCategory($category_meta_descriptions_other_level_one, "/\[,\]/");
                $category_meta_descriptions[] = array_shift($category_meta_descriptions_other_level_one);

                $category_head_titles = preg_split("/\[\|\]/", $category_head_titles);
                $category_head_titles = array_filter($category_head_titles);
                $category_head_titles_other_level_one = array_pop($category_head_titles);// 拿到最后一个
                $category_head_titles_other_level_one = findFirstCategory($category_head_titles_other_level_one, "/\[,\]/");
                $category_head_titles[] = array_shift($category_head_titles_other_level_one);// 拿到第一个

                $category_h1_titles = preg_split("/\[\|\]/", $category_h1_titles);
                $category_h1_titles = array_filter($category_h1_titles);
                $category_h1_titles_other_level_one = array_pop($category_h1_titles);
                $category_h1_titles_other_level_one = findFirstCategory($category_h1_titles_other_level_one, "/\[,\]/");
                $category_h1_titles[] = array_shift($category_h1_titles_other_level_one);
                $parent_id = 0;
                //添加分类里摘出的一级分类
                for ($i = 0; $i < count($category_name_other_level_one); $i++) {
                    $parent_id = 0;
                    $category_name = u_utils::getArrayIndexValue($category_name_other_level_one, $i);
                    $category_description = u_utils::getArrayIndexValue($category_descriptions_other_level_one, $i);
                    $category_short_description = u_utils::getArrayIndexValue($category_short_descriptions_other_level_one, $i);
                    $category_meta_description = u_utils::getArrayIndexValue($category_meta_descriptions_other_level_one, $i);
                    $category_head_title = u_utils::getArrayIndexValue($category_head_titles_other_level_one, $i);
                    $category_h1_title = u_utils::getArrayIndexValue($category_h1_titles_other_level_one, $i);
                    insertOrUpdateSimpleCategory($product_info, $category_name, $parent_id, $category_description, $category_short_description,
                        $category_meta_description, $category_head_title, $category_h1_title, false);
                }
                //添加多级分类数据及关系
                $parent_id = 0;
                for ($i = 0; $i < count($categorie_names); $i++) {
                    $category_name = u_utils::getArrayIndexValue($categorie_names, $i);
                    $category_description = u_utils::getArrayIndexValue($category_descriptions, $i);
                    $category_short_description = u_utils::getArrayIndexValue($category_short_descriptions, $i);
                    $category_meta_description = u_utils::getArrayIndexValue($category_meta_descriptions, $i);
                    $category_head_title = u_utils::getArrayIndexValue($category_head_titles, $i);
                    $category_h1_title = u_utils::getArrayIndexValue($category_h1_titles, $i);
                    insertOrUpdateSimpleCategory($product_info, $category_name, $parent_id, $category_description, $category_short_description,
                        $category_meta_description, $category_head_title, $category_h1_title);
                }
            }
        }
    }// import_categories end.

    function findFirstCategory($category_name_tmp, $pattern)
    {
        $category_name_tmp = preg_split($pattern, $category_name_tmp);
        $category_name_tmp = array_filter($category_name_tmp);

        return $category_name_tmp;
    }

    /**
     * 添加单个分类相关数据，该方法会构建层级结构
     * @param $product_id       商品id
     * @param $category_name     分类名
     * @param $parent_id        分类的父类
     * @param $category_description     分类的详细描述
     * @param $category_short_description   分类的简介
     * @param $category_meta_description    分类meta描述
     * @param $category_head_title  分类的<head>标签里的内容
     * @param $category_h1_title    分类在页面上显示的标题
     * @param $isTree   是否为添加层级结构。
     */
    function insertOrUpdateSimpleCategory(&$product_info, $category_name, &$parent_id, $category_description, $category_short_description,
                                          $category_meta_description, $category_head_title, $category_h1_title, $isTree = true)
    {
        if (!empty($category_name)) {//如果category_name不为空，才进行数据操作，否则不进行任何操作
            // 查找每个分类数据，如果找到，则跳过，如果没找到，则添加
            $sql = "SELECT id,parent_id FROM " . DB_TABLE_CATEGORIES . " 
                    WHERE id in (SELECT category_id FROM " . DB_TABLE_CATEGORIES_INFO . " 
                    WHERE NAME = '%s' ) and parent_id = %d";
            $parameter_values = array($category_name, $parent_id);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::fetch(database::query($sql));
            $category_id = $result['id'];
            $product_id = $product_info['id'];
            if (empty($result)) {//记录不存在，添加分类数据
                // 1. 插入新的分类到lc_categories，插入的数据有：langunge_code,date_created,插入完成后拿到id值。
                $sql = "insert into " . DB_TABLE_CATEGORIES
                    . " (parent_id, dock, status, list_style, date_created) values (%d,'%s',%d,'%s','%s')";

                $parameter_values = array($parent_id, 'tree', 1, 'column', date('Y-m-d H:i:s'));
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
                $category_id = database::insert_id();//query("select max(id) from " . DB_TABLE_CATEGORIES);//2. 拿到刚才添加的id。
                // 如果$id不为null且不是""，这设置parent_id
                if (isset($category_id) && !empty($category_id)) {
                    $parent_id = $category_id;//改变父类id
                }
                // 新增数据到lc_categories_info表。先测试通过，后期再看如何优化。
                $sql = "insert into " . DB_TABLE_CATEGORIES_INFO . " (category_id, language_code, name, description, short_description, meta_description, head_title, h1_title) 
                  values(%d,'%s','%s','%s','%s','%s','%s','%s')";
                $parameter_values = array($category_id, 'en',
                    $category_name, $category_description, $category_short_description, $category_meta_description,
                    $category_head_title, $category_h1_title);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                database::query($sql);
                //echo 'Creating new category: ' . $category_name . PHP_EOL;
            } else {
                if ($isTree == true) {//如果是添加有层级关系的分类，则父类id等于当前找到的id。
                    $parent_id = $category_id;
                } else {//如果是添加平级关系的分类，则父类id为当前的父类id
                    $parent_id = $category_id;
                }
                /**----------------------------------------------------------------------------------*/
                // update..  lc_categories_info
                updateCategoryInfo($category_id, $category_description, $category_short_description,
                    $category_meta_description, $category_head_title, $category_h1_title);

               // echo "Updated  category :" . $category_name . " default_category_id:" . $product_info['default_category_id'] . PHP_EOL;
            }
            $product_info['default_category_id'] = $category_id;// seting default_category_id for product.

            //--------------- 添加产品和分类的关系，涉及的表是 products_to_categories ------------
            // 1. 检查products_to_categories表里是否存在product_id 和 category_id 数据，如果存在则忽略，如果不存在则加上。
            $sql = "select product_id from " . DB_TABLE_PRODUCTS_TO_CATEGORIES . " where product_id=%d and category_id=%d";
            $parameter_values = array($product_id, $category_id);
            $sql = u_utils::builderSQL($sql, $parameter_values);
            $result = database::fetch(database::query($sql));
            if (empty($result)) {// 添加分类和商品的关联 lc_products_to_categories表
                $sql = "INSERT INTO " . DB_TABLE_PRODUCTS_TO_CATEGORIES . " (product_id, category_id) VALUES (%d, %d)";
                $parameter_values = array($product_id, $category_id);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
            }
        }
    }

    function updateCategoryInfo($category_id, $category_description, $category_short_description, $category_meta_description, $category_head_title, $category_h1_title)
    {
        $sql = "update ".DB_TABLE_CATEGORIES_INFO." SET description = '%s', short_description = '%s',
                meta_description = '%s', head_title = '%s',h1_title = '%s' WHERE category_id = %d";
        $parameter_values = array($category_description, $category_short_description,
            $category_meta_description, $category_head_title, $category_h1_title, $category_id);
        $sql = u_utils::builderSQL($sql, $parameter_values);
        $result = database::query($sql);
    }

    /**
     * @param $product_info
     */
    function updateProductOther(&$product_info, &$product_map)
    {
        // 通常情况下，只在第一次做更新即可。也不应该出现每个规格的数据都有不同数据，同时数据库表结构也不支持不同规格的不同价格等。
        $product_code = $product_info['code'];
        if (!isset($product_map[$product_code])) {
            //查找一下数据是否一致，如果一致，则不做处理，不一致则更新
            $product_groups = $product_info['product_groups'];
            $product_id = $product_info['id'];
            $price = $product_info['prices'][0];
            // 判断$product_groups 是否最后一个是逗号，如果是则截取，不是则不处理。
            $lastIndex = strripos($product_groups, ",");//拿到索引0开始
            $count = strlen($product_groups);//拿到长度,1开始，所以会比index大1.
            if ($lastIndex === $count - 1) {
                $product_groups = substr($product_groups, 0, -1);//去除逗号
            }
            //1. Update products.default_category_id,product_groups field

            $sql = "update " . DB_TABLE_PRODUCTS . " set default_category_id=%d,product_groups='%s' where id=%d";
            $parameter_values =  array($product_info['default_category_id'],
                $product_groups, $product_info['id']);
            $sql = u_utils::builderSQL($sql,$parameter_values);
            database::query($sql);

            //2. Update lc_products_prices table;
            $sql = "select id from " . DB_TABLE_PRODUCTS_PRICES . " where product_id=" . $product_id;
            $result = database::fetch(database::query($sql));
            if (empty($result)) {
                $sql = "insert into " . DB_TABLE_PRODUCTS_PRICES . " (product_id,USD,EUR) values(%d,%.4f,0.000)";
                $parameter_values = array($product_id, $price);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
            } else {
                $sql = "update " . DB_TABLE_PRODUCTS_PRICES . "set USD=%.4f where product_id=%d";
                $parameter_values = array($price, $product_id);
                $sql = u_utils::builderSQL($sql, $parameter_values);
                $result = database::query($sql);
            }
        }
    }

    /**
     * @deprecated  暂时弃用
     * @param $product_info
     * @return string
     */
//    function md5Product(&$product_info)
//    {
//        //name,short_description,description,attributes,head_title,meta_description
//        $md5_str = $product_info['name'] . $product_info['short_description'] . $product_info['description']
//            . $product_info['attributes'] . $product_info['head_title'] . $product_info['meta_description'];
//
//        return md5($md5_str);
//    }
?>
<h1><?php echo $app_icon; ?><?php echo language::translate('title_csv_import_export', 'CSV Import/Export'); ?></h1>
<!-- import or export csv html content 2018 17:07 zn add annotation in here -->
<div class="row">
    <!--import products -->
    <div class="col-md-6">
        <h2><?php echo language::translate('title_products', 'Products'); ?></h2>
        <div class="row">
            <div class="col-md-6">
                <fieldset class="well">
                    <legend><?php echo language::translate('title_import_from_csv', 'Import From CSV'); ?></legend>
                    <?php echo functions::form_draw_form_begin('import_products_form', 'post', '', true); ?>

                    <div class="form-group">
                        <label><?php echo language::translate('title_csv_file', 'CSV File'); ?></label>
                        <?php echo functions::form_draw_file_field('file'); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_delimiter', 'Delimiter'); ?></label>
                        <?php echo functions::form_draw_select_field('delimiter', array(array(language::translate('title_auto', 'Auto') . ' (' . language::translate('text_default', 'default') . ')', ''), array(','), array(';'), array('TAB', "\t"), array('|')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_enclosure', 'Enclosure'); ?></label>
                        <?php echo functions::form_draw_select_field('enclosure', array(array('" (' . language::translate('text_default', 'default') . ')', '"')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_escape_character', 'Escape Character'); ?></label>
                        <?php echo functions::form_draw_select_field('escapechar', array(array('" (' . language::translate('text_default', 'default') . ')', '"'), array('\\', '\\')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_charset', 'Charset'); ?></label>
                        <?php echo functions::form_draw_encodings_list('charset', !empty($_POST['charset']) ? true : 'UTF-8', false); ?>
                    </div>

<!--                    <div class="form-group">-->
<!--                        <label>--><?php //echo functions::form_draw_checkbox('insert_products', 'true', true); ?><!----><?php //echo language::translate('text_insert_new_products', 'Insert new products'); ?><!--</label>-->
<!--                    </div>-->

                    <?php echo functions::form_draw_button('import_products', language::translate('title_import', 'Import'), 'submit'); ?>

                    <?php echo functions::form_draw_form_end(); ?>

                </fieldset>
            </div><!-- import products end-->
            <!--export products -->
            <div class="col-md-6">
                <fieldset class="well">
                    <legend><?php echo language::translate('title_export_to_csv', 'Export To CSV'); ?></legend>

                    <?php echo functions::form_draw_form_begin('export_products_form', 'post'); ?>

                    <div class="form-group">
                        <label><?php echo language::translate('title_language', 'Language'); ?></label>
                        <?php echo functions::form_draw_languages_list('language_code', true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_currency', 'Currency'); ?></label>
                        <?php echo functions::form_draw_currencies_list('currency_code', true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_delimiter', 'Delimiter'); ?></label>
                        <?php echo functions::form_draw_select_field('delimiter', array(array(', (' . language::translate('text_default', 'default') . ')', ','), array(';'), array('TAB', "\t"), array('|')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_enclosure', 'Enclosure'); ?></label>
                        <?php echo functions::form_draw_select_field('enclosure', array(array('" (' . language::translate('text_default', 'default') . ')', '"')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_escape_character', 'Escape Character'); ?></label>
                        <?php echo functions::form_draw_select_field('escapechar', array(array('" (' . language::translate('text_default', 'default') . ')', '"'), array('\\', '\\')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_charset', 'Charset'); ?></label>
                        <?php echo functions::form_draw_encodings_list('charset', !empty($_POST['charset']) ? true : 'UTF-8', false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_line_ending', 'Line Ending'); ?></label>
                        <?php echo functions::form_draw_select_field('eol', array(array('Win'), array('Mac'), array('Linux')), true, false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo language::translate('title_output', 'Output'); ?></label>
                        <?php echo functions::form_draw_select_field('output', array(array(language::translate('title_file', 'File'), 'file'), array(language::translate('title_screen', 'Screen'), 'screen')), true, false); ?>
                    </div>

                    <?php echo functions::form_draw_button('export_products', language::translate('title_export', 'Export'), 'submit'); ?>

                    <?php echo functions::form_draw_form_end(); ?>
                </fieldset>
            </div><!--export products end-->
        </div>
    </div>
</div>