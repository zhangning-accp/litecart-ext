<?php
    // catalog->CSV Import/Export page and process import/export Categories or Products
    $product_map = array();// key:product_id,value:md5
    /**
     * 导入产品相关数据
     * @param $csv 产品csv数据
     */
    function importProducts($csv)
    {
        $line = 0;
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
                "md5" => ""
            );
            foreach ($row as $key => $value) {
                $product_info[$key] = $value;
            }
            //
            // -------------- 商品数据构建完毕 ---------------
            importProduct($product_info);
        }
    }

    function importProduct(&$product_info)
    {
        //------------ 添加商品信息 -----------------
        $product_id = $product_info['id'];
        $md5_value = md5Product($product_info);
        if (empty($product_id)) {
            if (!empty(findProductIdByMd5($md5_value))) {
                $product_id = findProductIdByMd5($md5_value);
                $product_info['id'] = $product_id;
            }
        }
        //1. 拆分image字符串
        $product_info['image'] = array_filter(preg_split("/\|/", $product_info['image']));
        if (empty($product_id)) {//新增
            // 新增，新增后，将product_id赋值给id
            $sql = "INSERT INTO %s (status,manufacturer_id,supplier_id,delivery_status_id,
                      sold_out_status_id,default_category_id,product_groups,keywords,
                      code,sku,mpn,upc,gtin,taric,quantity,quantity_unit_id,weight,
                      weight_class,dim_x,dim_y,dim_z,dim_class,purchase_price,purchase_price_currency_code,
                      tax_class_id,image,views,purchases,date_valid_from,date_valid_to,date_updated,date_created)
                       values (%d,%d,%d,%d,
                              %d,%d,%d,'%s',
                              uuid(),uuid(),'%s','%s','%s','%s',%d,%d,%d,
                              '%s',%d,%d,%d,'%s',%.2f,'%s',
                              %d,'%s',%d,%d,'%s','%s','%s','%s')";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS,
                $product_info['status'], $product_info['manufacturer_id'], $product_info['supplier_id'], $product_info['delivery_status_id'],
                $product_info['sold_out_status_id'], $product_info['default_category_id'], '', $product_info['keywords'],
                $product_info['mpn'], $product_info['upc'], $product_info['gtin'], $product_info['taric'],
                $product_info['quantity'], $product_info['quantity_unit_id'], $product_info['weight'],
                $product_info['weight_class'], $product_info['dim_x'], $product_info['dim_y'], $product_info['dim_z'],
                $product_info['dim_class'], $product_info['purchase_price'], $product_info['purchase_price_currency_code'],
                $product_info['tax_class_id'], $product_info['image'][0], $product_info['views'], $product_info['purchases'],
                $product_info['date_valid_from'], $product_info['date_valid_to'], $product_info['date_updated'],
                $product_info['date_created']));
            $result = database::query($sql);
            $product_id = database::insert_id();
            $product_info['id'] = $product_id;
            //2. 新增 product_info表数据
            $sql = "INSERT INTO %s (product_id,language_code,name,short_description,description,
                    head_title,meta_description,attributes)
                       values (%d,'%s','%s','%s','%s','%s','%s','%s')";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_INFO,
                $product_id, 'en', $product_info['name'], $product_info['short_description'],
                $product_info['description'], $product_info['head_title'],
                $product_info['meta_description'], $product_info['attributes']));
            $result = database::query($sql);

        } else {
            // TODO:避免多次更新,如果遇到id不同，但md5值相同怎么处理,这种情况忽略
            if (empty(findProductIdByMd5($md5_value))) {// 如果MD5值对应的value为空，表示是第一次更新。
                // 1. 对于product表，进行更新，
                $date = u_utils::getYMDHISDate();
                $sql = "UPDATE %s SET status = %d,quantity = %d,purchase_price = %d,image = '%s',date_updated = '%s' WHERE id = %d";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS,
                    $product_info['status'], $product_info['quantity'], $product_info['purchase_price'],
                    $product_info['image'][0], $date, $product_id));
                database::query($sql);
                // 更新product_info表
                $sql = "UPDATE %s SET name = '%s', short_description = '%s', description = '%s', head_title = '%s', 
                    meta_description = '%s', attributes = '%s' WHERE product_id = %d";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_INFO,
                    $product_info['name'], $product_info['short_description'], $product_info['description'],
                    $product_info['head_title'], $product_info['meta_description'], $product_info['attributes'], $product_id));
                database::query($sql);
            }
        }// TODO:以上代码测试通过 2018-09-18 14:50
        $product_map[$md5_value] = $product_id;
        addImages($product_info); //处理图片数据
        addOptionGroup($product_info,$md5_value);
        //echo "addProductGroup before:".$product_info['product_groups'].PHP_EOL;
        addProductGroup($product_info);
        //echo "addProductGroup after:".$product_info['product_groups'].PHP_EOL;
        addCategories($product_info);
        updateProductOther($product_info);


    }

    /**
     * 添加图片
     * @param $product_info 商品信息对象
     */
    function addImages(&$product_info)
    {
        //---------------------对于lc_products_images表，先删后增。---------------------
        $product_id = $product_info['id'];
        $sql = "delete from %s where product_id = %d";
        $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_IMAGES, $product_id));
        $result = database::query($sql);
        if (!empty($product_info['image']) && !empty($product_id)) {//删除表数据
            foreach ($product_info['image'] as $image) {//添加新数据
                $sql = "insert into %s (product_id,filename,checksum,priority) values(%d,'%s','%s',1)";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_IMAGES, $product_id, $image, ""));
                $result = database::query($sql);
            }
        }
    }

    /**
     * @param $product_info
     */
    function addOptionGroup(&$product_info,$md5_value)
    {
        //TODO: 需要重新梳理一下和option_group相关的表以及操作。
        // 这个要重新处理，相同规格的商品只能是一条记录，但这处理成了多条记录。解决办法，对
        //name short_description	description	attributes	head_title	meta_description
        // 这几个值进行md5校验。

        //option_groups  数据格式:   option_name(选项父类名):select_name(选项子类值)
        $product_id = $product_info['id'];
        $option_groups_str = $product_info['option_groups'];
        if(!empty($option_groups_str)) {
            $option_groups = preg_split("/:/", $option_groups_str);
            $option_name = $option_groups[0];
            $option_value = $option_groups[1];
            // 查找是否存在该数据选项，如果不存在，则添加，存在则不做处理
            //1. 需要查找lc_option_groups_info和lc_option_values_info。
            //类型是textarea和input的选项数据是放在lc_option_values表里。暂时不考虑这两种类型
            // 其它类型的选项数据是放在lc_option_values_info表里
            $sql = "select id,group_id from %s where name = '%s'";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_GROUPS_INFO, $option_name));
            $result = database::fetch(database::query($sql));
            $group_id = $result['group_id'];
            if (empty($result)) {// 如果不存在选项则添加，存在则忽略
                //添加如下数据到对应表：
                //1. `lc_option_groups`
                $sql = "insert into %s (function,required,sort,date_created) VALUES ('%s',1,'%s','%s')";
                $sql = u_utils::builderSQL($sql,
                    array(DB_TABLE_OPTION_GROUPS,'select','priority', $product_info['date_created']));
                $result = database::query($sql);//拿到id值
                $group_id = database::insert_id();
                //`lc_option_groups_info`
                $sql = "insert into %s (group_id,language_code,name,description) VALUES (%d,'en','%s','')";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_GROUPS_INFO, $group_id, $option_name));
                $result = database::query($sql);
                // 添加$option_name 数据结束
            }
            //添加$option_value 开始`lc_option_values`
            // 先查询$option_value在表lc_option_values_info是否存在，如果不存在，则添加，如果存在则忽略
            $sql = "SELECT %s.id as value_id,%s.group_id FROM %s INNER JOIN %s ON value_id = %s.id 
                AND %s.group_id = %d AND %s.name = '%s'";
            $sql = u_utils::builderSQL($sql, array(
                DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES,
                DB_TABLE_OPTION_VALUES_INFO, DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES,
                $group_id, DB_TABLE_OPTION_VALUES_INFO,$option_value));
            //$sql = "select id from %s where name = '%s'";
            //$sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES_INFO, DB_TABLE_OPTION_VALUES,$group_id,$option_value));
            $result = database::fetch(database::query($sql));
            if (empty($result)) {//如果$option_value不存在。则添加，如果存在则更新。
                $sql = "insert into %s (group_id,value,priority) VALUES (%d,'',1)";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES, $group_id));
                $result = database::query($sql);
                $value_id = database::insert_id();
                //`lc_option_values_info`
                $sql = "insert into %s (value_id,language_code,name) VALUES (%d,'en','%s')";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES_INFO, $value_id, $option_value));
                $result = database::query($sql);
                //$value_id = database::insert_id();
                // --------- value_info 添加结束 -------------------

                //关联商品操作 lc_products_options 和 lc_products_options_stock
                //1. 先关联lc_products_options，这样页面上购买商品是才有可选项。
                $sql = "insert into %s (product_id,group_id,value_id,price_operator,USD,
                                    priority,date_updated,date_created) 
                    VALUES (%d,%d,%d,'+',0.000,1,'%s','%s')";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_OPTIONS,
                    $product_id, $group_id, $value_id, $product_info['date_updated'], $product_info['date_created']));
                $result = database::query($sql);
                // 以上操作测试无误。

                //TODO: 关联lc_products_options_stock 操作。
                // 2. 关联lc_products_options_stock 操作。
//                $combination = $group_id . "-" . $value_id;
//                $sql = "INSERT INTO %s (product_id,combination,sku,weight,
//                                                weight_class,dim_x,dim_y,dim_z,dim_class,quantity,
//                                                priority,date_updated,date_created)
//                VALUES()";
//                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_OPTIONS_STOCK,
//                    $product_id, $combination, UUID(), 0,
//                    'kg', 0.00, 0.00, 0.00, 'cm', $product_info['quantity'],
//                    1, $product_info['date_updated'], $product_info['date_created']));
//                database::query($sql);
//                // 修改商品表里的总数
//                $sql = "update %s set quantity = quantity+%d where id = %d";
//                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS, $product_info['quantity'], $product_id));
//                database::query($sql);
            } else {//存在 选项值
                // 只更新数量，同时还要修改商品表里的总数
                //1. 查询选项对应的value_id和group_id.
//                $value_id = $result['value_id'];
//                $combination = $group_id . "-" . $value_id;
//                $sql = "select quantity from lc_products_options_stock where combination=" . $combination;
//                $result = database::fetch($sql);
//                $quantity = $result['quantity'];
//                $current_quantity = $product_info['quantity'];
//                $quantity = $current_quantity - $quantity;
//                $sql = "update " . DB_TABLE_PRODUCTS . " set quantity = quantity + " . $quantity . " where id=" . $product_id;
//                $result = database::query($sql);
            }
        }
    }

    /**
     * @param $product_info
     */
    function addProductGroup(&$product_info)
    {   //$group_name_str数据格式：gropu_name:child_1,child_2,child_n。如果 $group_name 为空，则不做处理
        $group_name = $product_info['product_groups'];
        $product_groups = "";
        if (!empty($group_name)) {
            // 添加product_groups 相关数据操作
            //1.在lc_product_groups_info表里查找是否存在，
            $group_name = preg_split("/:/", $group_name);
            $group_name = $group_name[0];

            $group_values = $group_name[1];
            $group_values = preg_split("/,/", $group_values);

            //查找$group_name 在lc_product_groups_info表里是否存在
            $sql = "select id,product_group_id from %s where name ='%s'";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_INFO, $group_name));
            $result = database::fetch(database::query($sql));
            $product_group_id = $result['product_group_id'];
            if (empty($result)) {// 数据不存在
                //添加$group_name 到`lc_product_groups` 和 `lc_product_groups_info`表
                $sql = "insert into %s (status,date_updated,date_created) values(1,'%s','%s')";
                $date = u_utils::getYMDHISDate();
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS, $date, $date));
                $result = database::query($sql);
                $product_group_id = database::insert_id();
                // 添加数据到lc_product_groups_info
                $sql = "insert into %s (product_group_id,language_code,name) values(%d,'en','%s')";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_INFO,
                    $product_group_id, $group_name));
                $result = database::query($sql);
            }
            //2. 查找子项是否存在，如果不存在则添加
            $sql = "select name from %s where name in (";
            $in = "";
            $in = "'" . join("','", array_values($group_values)) . "'";
            $in .= ")";
            $sql .= $in . " and product_group_value_id in (select id from %s where product_group_id=%d)";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_VALUES_INFO,
                DB_TABLE_PRODUCT_GROUPS_VALUES, $product_group_id));
            $result = database::fetch_full(database::query($sql), 'name');
            $diff = array_diff($group_values, $result);//寻找差值，如果result里没有，则添加
            if (!empty($diff)) {
                //添加子项  lc_product_groups_values
                foreach ($diff as $value_name) {
                    $date = u_utils::getYMDHISDate();
                    $sql = "insert into %s (product_group_id,date_updated,date_created) values(%d,'%s','%s')";
                    $sql = u_utils::builderSQL($sql,
                        array(DB_TABLE_PRODUCT_GROUPS_VALUES, $product_group_id, $date, $date));
                    $result = database::query($sql);
                    $group_value_id = database::insert_id();
                    // 添加子项 lc_product_groups_values_info
                    $sql = "insert into %s (`product_group_value_id`,`language_code`,`name`) values(%d,'en','%s')";
                    $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_VALUES_INFO,
                        $group_value_id, $value_name));
                    $result = database::query($sql);
                    //TODO: 这里确认一下 如果产品属于多个product_group时，product_groups的格式是：
                    //12-25,12-26,12-27,13-28,13-29
                    $product_groups .= $product_group_id . "-" . $group_value_id .",";
                }
            } // 以上代码简单测试通过 2018-09-17
            /**--------------添加product_groups 相关数据操作结束 ------------**/
            $product_info['product_groups'] = substr($product_groups,0,-1);;//重设product_groups内容
            echo "in addProductGroup product_groups:".$product_info['product_groups'].PHP_EOL;
        }
    }

    //-------------   category -----------------------------
    /**
     * 导入分类
     * @param $product_info
     * @param $isInsertNew 当数据不存在时是否插入新数据。如果true，当数据库找不到导入的数据时，则将新增数据到数据库.
     * 该方法测试通过。 2018-9-16 11:30
     */
    function addCategories(&$product_info)
    {
        $line = 0;
        $categorie_names = $product_info['categorie_names'];//"Rubber Ducks|Subcategory|shot,d,d,d";
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
        //检查各数组长度是否一致
//        if (count($categorie_names) !== count($category_descriptions)
//            && count($categorie_names) !== count($category_meta_descriptions)
//            && count($categorie_names) !== count($category_short_descriptions)
//            && count($categorie_names) !== count($category_head_titles)
//            && count($categorie_names) !== count($category_h1_titles)
//        ) {
//            echo "<div style='color:red'>categorie_names, category_descriptions, category_short_descriptions, category_meta_descriptions,
//                category_head_titles, category_h1_titles column count doesn't match. Please check !</div>";
//            exit;
//        } else {
            $parent_id = 0;
            //添加分类里摘出的一级分类
            for ($i = 0; $i < count($category_name_other_level_one); $i++) {
                $parent_id = 0;
                $category_name = $category_name_other_level_one[$i];
                $category_description = $category_descriptions_other_level_one[$i];
                $category_short_description = $category_short_descriptions_other_level_one[$i];
                $category_meta_description = $category_meta_descriptions_other_level_one[$i];
                $category_head_title = $category_head_titles_other_level_one[$i];
                $category_h1_title = $category_h1_titles_other_level_one[$i];
                insertOrUpdateSimpleCategory($product_info, $category_name, $parent_id, $category_description, $category_short_description,
                    $category_meta_description, $category_head_title, $category_h1_title, false);
            }
            //添加多级分类数据及关系
            $parent_id = 0;
            for ($i = 0; $i < count($categorie_names); $i++) {
                $category_name = $categorie_names[$i];
                $category_description = $category_descriptions[$i];
                $category_short_description = $category_short_descriptions[$i];
                $category_meta_description = $category_meta_descriptions[$i];
                $category_head_title = $category_head_titles[$i];
                $category_h1_title = $category_h1_titles[$i];
                insertOrUpdateSimpleCategory($product_info, $category_name, $parent_id, $category_description, $category_short_description,
                    $category_meta_description, $category_head_title, $category_h1_title);

            }
        //}


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
        // 查找每个分类数据，如果找到，则跳过，如果没找到，则添加
        $sql = "SELECT id,parent_id FROM %s WHERE id = (SELECT category_id FROM %s WHERE NAME = '%s' limit 1) and parent_id = %d limit 1";
        $sql = u_utils::builderSQL($sql, array(DB_TABLE_CATEGORIES, DB_TABLE_CATEGORIES_INFO, $category_name, $parent_id));
        $result = database::fetch(database::query($sql));
        $category_id = $result['id'];
        $product_id = $product_info['id'];
        if (empty($result)) {//记录不存在，添加分类数据
            // 1. 插入新的分类到lc_categories，插入的数据有：langunge_code,date_created,插入完成后拿到id值。
            $sql = "insert into " . DB_TABLE_CATEGORIES
                . " (parent_id, dock, status, list_style, date_created) values (%d,'%s',%d,'%s','%s')";
            $sql = u_utils::builderSQL($sql, array($parent_id, 'tree', 1, 'rows', date('Y-m-d H:i:s')));
            $result = database::query($sql);
            $category_id = database::insert_id();//query("select max(id) from " . DB_TABLE_CATEGORIES);//2. 拿到刚才添加的id。
            // 如果$id不为null且不是""，这设置parent_id
            if (isset($category_id) && !empty($category_id)) {//TODO:二级分类插入时，parent_id为0，上一次的赋值失败。
                $parent_id = $category_id;//改变父类id
            }
            // 新增数据到lc_categories_info表。先测试通过，后期再看如何优化。
            $sql = "insert into %s (category_id, language_code, name, description, short_description, meta_description, head_title, h1_title) 
                  values(%d,'%s','%s','%s','%s','%s','%s','%s')";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_CATEGORIES_INFO, $category_id, 'en',
                $category_name, $category_description, $category_short_description, $category_meta_description,
                $category_head_title, $category_h1_title));
            database::query($sql);
            echo 'Creating new category: ' . $category_name . PHP_EOL;
            //--------------- 更新 default_category_id 同时更新 lc_products_to_categories
            // TODO: 还要更新 lc_products_to_categories 表和product 表里的default_category_id
            // 1. 检查是否存在product_id 和 category_id 数据存在
            $sql = "select product_id from %s where product_id=%d and category_id=%d";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_TO_CATEGORIES, $product_id, $category_id));
            $result = database::fetch(database::query($sql));
            if(empty($result)) {
                $sql = "INSERT INTO %s (product_id, category_id) VALUES (%d, %d)";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_TO_CATEGORIES, $product_id, $category_id));
                $result = database::query($sql);
            }

        } else {// update..  lc_categories_info
            if ($isTree == true) {//如果是添加有层级关系的分类，则父类id等于当前找到的id。
                $parent_id = $category_id;
            } else {//如果是添加平级关系的分类，则父类id为当前的父类id
                $parent_id = $category_id;
            }
            updateCategoryInfo($category_id, $category_description, $category_short_description,
                $category_meta_description, $category_head_title, $category_h1_title);


            echo "Updated  category :" . $category_name . "default_category_id:".$product_info['default_category_id'].PHP_EOL;
        }
        $product_info['default_category_id'] = $category_id;
        echo "default_category_id:".$product_info['default_category_id'].PHP_EOL;
    }

    function updateCategoryInfo($category_id, $category_description, $category_short_description, $category_meta_description, $category_head_title, $category_h1_title)
    {
        $sql = "update %s SET description = '%s', short_description = '%s',
                meta_description = '%s', head_title = '%s',h1_title = '%s' WHERE category_id = %d";
        $sql = u_utils::builderSQL($sql, array(DB_TABLE_CATEGORIES_INFO, $category_description, $category_short_description,
            $category_meta_description, $category_head_title, $category_h1_title, $category_id));
        $result = database::query($sql);
    }

    /**
     * @param $product_info
     */
    function updateProductOther(&$product_info)
    {
        //1. Update products.default_category_id,
        //2. Update products.product_groups,
        //echo "in updateProductOther product_groups:".$product_info['product_groups'].",id:".$product_info['id'].PHP_EOL;
        $product_groups = $product_info['product_groups'];

        $sql = "update %s set default_category_id=%d,product_groups='%s' where id=%d";
        $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS,
            $product_info['default_category_id'], $product_groups, $product_info['id']));
        database::query($sql);
        //3. Update
    }

    /**
     * 完整导入分类和产品
     */
    function importCategoriesAndProducts()
    {
        try {
            if (!isset($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
                throw new Exception(language::translate('error_must_select_file_to_upload', 'You must select a file to upload'));
            }

            ob_clean();

            header('Content-Type: text/plain; charset=' . language::$selected['charset']);

            echo "CSV Import\r\n"
                . "----------\r\n";

            $csv = file_get_contents($_FILES['file']['tmp_name']);
            $csv = functions::csv_decode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset']);
            //importCategories($csv);
            importProducts($csv);
            exit;
        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    /**
     * 导出分类
     */
    function exportCategories()
    {
        ///if (isset($_POST['export_categories'])) {

        try {
            if (empty($_POST['language_code'])) throw new Exception(language::translate('error_must_select_a_language', 'You must select a language'));

            //$csv = array();

            $categories_query = database::query("select id from " . DB_TABLE_CATEGORIES . " order by parent_id;");
            while ($category = database::fetch($categories_query)) {
                $category = new ref_category($category['id'], $_POST['language_code']);
                // 根据产品id筛选分类
                $csv[] = array(
                    'id' => $category->id,
                    'status' => $category->status,
                    'parent_id' => $category->parent_id,
                    'code' => $category->code,
                    'name' => $category->name,
                    'keywords' => implode(',', $category->keywords),
                    'short_description' => $category->short_description,
                    'description' => $category->description,
                    'meta_description' => $category->meta_description,
                    'head_title' => $category->head_title,
                    'h1_title' => $category->h1_title,
                    'image' => $category->image,
                    'priority' => $category->priority,
                    'language_code' => $_POST['language_code'],
                );
            }

            ob_clean();

//      if ($_POST['output'] == 'screen') {
//        header('Content-Type: text/plain; charset=' . $_POST['charset']);
//      } else {
//        header('Content-Type: application/csv; charset=' . $_POST['charset']);
//        header('Content-Disposition: attachment; filename=categories-' . $_POST['language_code'] . '.csv');
//      }
//
//      switch ($_POST['eol']) {
//        case 'Linux':
//          echo functions::csv_encode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset'], "\r");
//          break;
//        case 'Mac':
//          echo functions::csv_encode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset'], "\n");
//          break;
//        case 'Win':
//        default:
//          echo functions::csv_encode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset'], "\r\n");
//          break;
//      }

//      exit;
            return $csv;
        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
        //}

    }

    /*
     * 导出产品
     */
    function exportProducts()
    {
        //export_products start
        //if (isset($_POST['export_products'])) {

        try {

            if (empty($_POST['language_code'])) throw new Exception(language::translate('error_must_select_a_language', 'You must select a language'));

            //$csv = array();
            // select p.id from `litecart`.`lc_products` p left join `litecart`.`lc_products_info` pi on (pi.product_id = p.id and pi.language_code = 'en') order by pi.name;
            $query_sql = "select p.id from " . DB_TABLE_PRODUCTS . " p left join "
                . DB_TABLE_PRODUCTS_INFO
                . " pi on (pi.product_id = p.id and pi.language_code = '"
                . database::input($_POST['language_code']) . "') order by pi.name;";
            $products_query = database::query($query_sql);

            while ($product = database::fetch($products_query)) {
                $product = new ref_product($product['id'], $_POST['language_code'], $_POST['currency_code']);

                $csv[] = array(
                    'id' => $product->id,
                    'status' => $product->status,
                    'categories' => implode(',', array_keys($product->categories)),
                    'product_groups' => implode(',', array_keys($product->product_groups)),
                    'manufacturer_id' => $product->manufacturer_id,
                    'supplier_id' => $product->supplier_id,
                    'code' => $product->code,
                    'sku' => $product->sku,
                    'mpn' => $product->mpn,
                    'gtin' => $product->gtin,
                    'taric' => $product->taric,
                    'name' => $product->name,
                    'short_description' => $product->short_description,
                    'description' => $product->description,
                    'keywords' => implode(',', $product->keywords),
                    'attributes' => $product->attributes,
                    'head_title' => $product->head_title,
                    'meta_description' => $product->meta_description,
                    'images' => implode(';', $product->images),
                    'purchase_price' => $product->purchase_price,
                    'purchase_price_currency_code' => $product->purchase_price_currency_code,
                    'price' => $product->price,
                    'tax_class_id' => $product->tax_class_id,
                    'quantity' => $product->quantity,
                    'quantity_unit_id' => $product->quantity_unit['id'],
                    'weight' => $product->weight,
                    'weight_class' => $product->weight_class,
                    'delivery_status_id' => $product->delivery_status_id,
                    'sold_out_status_id' => $product->sold_out_status_id,
                    'language_code' => $_POST['language_code'],
                    'currency_code' => $_POST['currency_code'],
                    'date_valid_from' => $product->date_valid_from,
                    'date_valid_to' => $product->date_valid_to,
                );
            }

            ob_clean();

            if ($_POST['output'] == 'screen') {
                header('Content-Type: text/plain; charset=' . $_POST['charset']);
            } else {
                header('Content-Type: application/csv; charset=' . $_POST['charset']);
                header('Content-Disposition: attachment; filename=products-' . $_POST['language_code'] . '.csv');
            }

            switch ($_POST['eol']) {
                case 'Linux':
                    echo functions::csv_encode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset'], "\r");
                    break;
                case 'Mac':
                    echo functions::csv_encode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset'], "\n");
                    break;
                case 'Win':
                default:
                    echo functions::csv_encode($csv, $_POST['delimiter'], $_POST['enclosure'], $_POST['escapechar'], $_POST['charset'], "\r\n");
                    break;
            }

            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
        //}//export_products end.

    }

    /**
     * 完整导出分类和产品
     */
    function exportCategoriesAndProducts()
    {
        exportProducts();
        exportCategories();
    }

    function md5Product($product_info)
    {
        //name,short_description,description,attributes,head_title,meta_description
        $md5_str = $product_info['name'] . $product_info['short_description'] . $product_info['description']
            . $product_info['attributes'] . $product_info['head_title'] . $product_info['meta_description'];

        return md5($md5_str);
    }

    /**
     * 查找对应的id是否存在md5值。如果不存在，返回""或null,存在则返回对应的product_id值
     * @param $md5_value
     */
    function findProductIdByMd5($md5_value)
    {
        return $product_map[$md5_value];
    }

    // import or export run.
    if (isset($_POST['import_products'])) {
        importCategoriesAndProducts();
    } elseif (isset($_POST['export_products'])) {
        //$csv_array = builderExportCSVArray();
        exportCategoriesAndProducts();
    }
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

                    <div class="form-group">
                        <label><?php echo functions::form_draw_checkbox('insert_products', 'true', true); ?><?php echo language::translate('text_insert_new_products', 'Insert new products'); ?></label>
                    </div>

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