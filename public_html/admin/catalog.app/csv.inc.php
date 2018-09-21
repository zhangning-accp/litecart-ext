<?php
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
     * image:* required.
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
    function importProducts($csv)
    {
        $product_map = array();
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
                "md5" => "",
                "price" => 0
            );
            foreach ($row as $key => $value) {
                $product_info[$key] = trim($value);
            }
            // -------------- 商品数据构建完毕 ---------------
            $product_name = $product_info['name'];
            if(!empty($product_name)) {
                importProduct($product_info, $product_map);
            }
        }
    }

    function importProduct(&$product_info, &$product_map)
    {
        //------------ 添加商品信息 -----------------
        $product_id = $product_info['id'];
        $md5_value = md5Product($product_info);
        if (empty($product_id)) {
            $product_id = $product_map["'" . $md5_value . "'"];
            if (!empty($product_id)) {
                $product_info['id'] = $product_id;
            }
        }
        //1. 拆分image字符串
        $product_info['image'] = array_filter(preg_split("/\|/", $product_info['image']));
        $main_image = "";
        if(!empty($product_info['image'])) {
            $main_image = $product_info['image'][0];
        }
        if (empty($product_id)) {//新增
            // 新增，新增后，将product_id赋值给id
            $sql = "INSERT INTO " . DB_TABLE_PRODUCTS . " (status,manufacturer_id,supplier_id,delivery_status_id,
                      sold_out_status_id,default_category_id,product_groups,keywords,
                      code,sku,mpn,upc,gtin,taric,quantity,quantity_unit_id,weight,
                      weight_class,dim_x,dim_y,dim_z,dim_class,purchase_price,purchase_price_currency_code,
                      tax_class_id,image,views,purchases,date_valid_from,date_valid_to,date_updated,date_created)
                       values (%d,%d,%d,%d,
                              %d,%d,%d,'%s',
                              uuid(),uuid(),'%s','%s','%s','%s',%.4f,%d,%d,
                              '%s',%d,%d,%d,'%s',%.4f,'%s',
                              %d,'%s',%d,%d,'%s','%s','%s','%s')";
            $sql = u_utils::builderSQL($sql, array(
                $product_info['status'], $product_info['manufacturer_id'], $product_info['supplier_id'], $product_info['delivery_status_id'],
                $product_info['sold_out_status_id'], $product_info['default_category_id'], '', $product_info['keywords'],
                $product_info['mpn'], $product_info['upc'], $product_info['gtin'], $product_info['taric'],
                0, $product_info['quantity_unit_id'], $product_info['weight'],
                $product_info['weight_class'], $product_info['dim_x'], $product_info['dim_y'], $product_info['dim_z'],
                $product_info['dim_class'], $product_info['price'], $product_info['purchase_price_currency_code'],
                $product_info['tax_class_id'], $main_image, $product_info['views'], $product_info['purchases'],
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
            if (empty($product_map["'" . $md5_value . "'"])) {// 如果MD5值对应的value为空，表示是第一次更新。
                // 1. 对于product表，进行更新，
                $date = u_utils::getYMDHISDate();
                $sql = "UPDATE " . DB_TABLE_PRODUCTS . "SET status = %d,quantity = %d,purchase_price = %.4f,image = '%s',date_updated = '%s' WHERE id = %d";
                $sql = u_utils::builderSQL($sql, array($product_info['status'], $product_info['quantity'],
                    $product_info['price'], $product_info['image'][0], $date, $product_id));
                database::query($sql);
                // 更新product_info表
                $sql = "UPDATE " . DB_TABLE_PRODUCTS_INFO . " SET name = '%s', short_description = '%s', description = '%s', head_title = '%s', 
                    meta_description = '%s', attributes = '%s' WHERE product_id = %d";
                $sql = u_utils::builderSQL($sql, array($product_info['name'], $product_info['short_description'],
                    $product_info['description'], $product_info['head_title'], $product_info['meta_description'],
                    $product_info['attributes'], $product_id));
                database::query($sql);
            }
        }// 以上代码测试通过 2018-09-18 14:50
        $product_map["'" . $md5_value . "'"] = $product_id;
        addImages($product_info); //处理图片数据
        addOptionGroup($product_info);
        //echo "addProductGroup before:".$product_info['product_groups'].PHP_EOL;
        addProductGroup($product_info);
        //echo "addProductGroup after:".$product_info['product_groups'].PHP_EOL;
        addCategories($product_info);
        updateProductOther($product_info);
    }

    /**
     * 添加图片，测试多次无异常，放心用
     * @param $product_info 商品信息对象
     */
    function addImages($product_info)
    {
        //---------------------对于lc_products_images表，先删后增。---------------------
        $product_id = $product_info['id'];
        if(!empty($product_info['image']) && !empty($product_id)) {
            $sql = "delete from %s where product_id = %d";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCTS_IMAGES, $product_id));
            $result = database::query($sql);
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
    function addOptionGroup($product_info)
    {
        //option_groups  数据格式:   group_name(选项组名):option_name(选项名)
        $product_id = $product_info['id'];
        $option_groups_str = $product_info['option_groups'];
        $date = u_utils::getYMDHISDate();
        $combination = "";
        $value_id = "";
        if (!empty($option_groups_str)) {
            $option_groups = preg_split("/:/", $option_groups_str);
            $group_name = $option_groups[0];
            $option_name = $option_groups[1];
            // 查找是否存在该数据选项，如果不存在，则添加，存在则不做处理
            //1. 需要查找lc_option_groups_info和lc_option_values_info。
            //类型是textarea和input的选项数据是放在lc_option_values表里。暂时不考虑这两种类型
            // 其它类型的选项数据是放在lc_option_values_info表里
            $sql = "select id,group_id from " . DB_TABLE_OPTION_GROUPS_INFO . " where name = '%s'";
            $sql = u_utils::builderSQL($sql, array($group_name));
            $result = database::fetch(database::query($sql));
            $group_id = $result['group_id'];
            if (empty($result)) {// 如果不存在选项则添加，存在则忽略
                //添加如下数据到对应表：
                //1. `lc_option_groups`
                $sql = "insert into " . DB_TABLE_OPTION_GROUPS . " (function,required,sort,date_created) VALUES ('%s',1,'%s','%s')";
                $sql = u_utils::builderSQL($sql,
                    array('select', 'priority', $product_info['date_created']));
                $result = database::query($sql);//拿到id值
                $group_id = database::insert_id();
                //`lc_option_groups_info`
                $sql = "insert into %s (group_id,language_code,name,description) VALUES (%d,'en','%s','')";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_GROUPS_INFO, $group_id, $group_name));
                $result = database::query($sql);
                // 添加$option_name 数据结束
            }
            //添加$option_value 开始`lc_option_values`
            // 先查询$option_value在表lc_option_values_info是否存在，如果不存在，则添加，如果存在则忽略
            /*
             * SELECT `litecart`.`lc_option_values`.id as value_id,`litecart`.`lc_option_values`.group_id
             * FROM `litecart`.`lc_option_values_info` INNER JOIN `litecart`.`lc_option_values`
             *  ON value_id = `litecart`.`lc_option_values`.id
                AND `litecart`.`lc_option_values`.group_id = 115 AND `litecart`.`lc_option_values_info`.name = 'M'
             */
            $sql = "SELECT %s.id as value_id,%s.group_id FROM %s INNER JOIN %s ON value_id = %s.id 
                AND %s.group_id = %d AND %s.name = '%s'";
            $sql = u_utils::builderSQL($sql, array(
                DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES,
                DB_TABLE_OPTION_VALUES_INFO, DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES, DB_TABLE_OPTION_VALUES,
                $group_id, DB_TABLE_OPTION_VALUES_INFO, $option_name));
            $result = database::fetch(database::query($sql));
            if (empty($result)) {//如果$option_name不存在。则添加，如果存在则更新。
                // 添加到 option_values 表
                $sql = "insert into " . DB_TABLE_OPTION_VALUES . " (group_id,value,priority) VALUES (%d,'',1)";
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES, $group_id));
                $result = database::query($sql);
                $value_id = database::insert_id();
                //添加到`lc_option_values_info`
                $sql = "insert into " . DB_TABLE_OPTION_VALUES_INFO . " (value_id,language_code,name) VALUES (%d,'en','%s')";
                $sql = u_utils::builderSQL($sql, array($value_id, $option_name));
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
            $sql = u_utils::builderSQL($sql, array($product_id, $group_id, $value_id));
            $result = database::fetch(database::query($sql));
            if (empty($result)) {// 导入是应该不会存在更新问题，所以此处忽略
                $sql = "insert into" . DB_TABLE_PRODUCTS_OPTIONS . " (product_id,group_id,value_id,price_operator,USD,
                                        priority,date_updated,date_created) 
                        VALUES (%d,%d,%d,'+',0.000,1,'%s','%s')";
                $sql = u_utils::builderSQL($sql, array($product_id, $group_id, $value_id, $date, $date));
                $result = database::query($sql);
            }
            // ----------------------------------------------------------------------------
            $combination = $group_id . "-" . $value_id;
            // 关联 lc_products_options_stock，首先检查是否有对应的stock记录，如果有，则更新，如果没有则添加
            $sql = "SELECT product_id,combination FROM " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " WHERE product_id=%d AND combination ='%s'";
            $sql = u_utils::builderSQL($sql, array($product_id, $combination));
            $result = database::fetch_full(database::query($sql));
            if (empty($result)) {
                $sql = "INSERT INTO " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " (product_id,combination,sku,weight,
                                                weight_class,dim_x,dim_y,dim_z,dim_class,quantity,
                                                priority,date_updated,date_created)
                                        VALUES(%d,'%s',UUID(),%d,'%s',
                                              '%s',%.4f,%.4f,%.4f,'%s',%d,'%s','%s')";
                $sql = u_utils::builderSQL($sql, array($product_id, $combination, $product_info['weight'],
                    $product_info['weight_class'], $product_info['dim_x'], $product_info['dim_y'], $product_info['dim_z'], $product_info['dim_class'], $product_info['quantity'],
                    1, $date, $date));
                database::query($sql);
                // 修改商品表里的总数, 新增的时候直接加，只有修改的时候需要计算差值。因为可能库存数量会比原来的减少。
                $sql = "update " . DB_TABLE_PRODUCTS . " set quantity = quantity+%d,date_updated='%s' where id = %d";
                $sql = u_utils::builderSQL($sql, array($product_info['quantity'], $date, $product_id));
                database::query($sql);
            } else {
                $current_quantity = $product_info['quantity'];
                //1. 查询stock 表里选项对应的value_id和group_id 的 quantity 值，因为可能更新时会有所减少
                $sql = "select quantity from " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " where combination='" . $combination . "' and product_id=" . $product_id;
                $result = database::fetch(database::query($sql));
                $quantity = $result['quantity'];

                // 更新库存
                $sql = "update " . DB_TABLE_PRODUCTS_OPTIONS_STOCK . " set quantity = %.4f,date_updated='%s' 
                                                                        where product_id=%d and combination='%s'";
                $sql = u_utils::builderSQL($sql, array($product_info['quantity'], $date, $product_id, $combination));
                database::query($sql);
                // 计算差值
                $quantity = $current_quantity - $quantity;
                $sql = "update " . DB_TABLE_PRODUCTS . " set quantity = quantity + " . $quantity . " where id=" . $product_id;
                $result = database::query($sql);
            }
        }
    }

    /**
     * @param $product_info
     */
    function addProductGroup(&$product_info)
    {   //$group_name_str数据格式：gropu_name:child_1,child_2,child_n|gropu_name:child_1,child_2,child_n。如果 $group_name 为空，则不做处理
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
                $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_INFO, $group_name));
                $result = database::fetch(database::query($sql));
                $product_group_id = $result['product_group_id'];
                if (empty($result)) {// group_name数据不存在
                    //添加$group_name 到lc_product_groups表
                    $sql = "insert into %s (status,date_updated,date_created) values(1,'%s','%s')";
                    $date = u_utils::getYMDHISDate();
                    $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS, $date, $date));
                    $result = database::query($sql);
                    $product_group_id = database::insert_id();
                    // 添加$group_name到lc_product_groups_info
                    $sql = "insert into %s (product_group_id,language_code,name) values(%d,'en','%s')";
                    $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_INFO,
                        $product_group_id, $group_name));
                    $result = database::query($sql);
                }
                //2. 查找子项是否存在，如果不存在则添加. // 改为全部查，然后做对比。
                $sql = "SELECT product_group_value_id,`name` FROM " . DB_TABLE_PRODUCT_GROUPS_VALUES_INFO . " 
                INNER JOIN " . DB_TABLE_PRODUCT_GROUPS_VALUES . " 
	            ON product_group_value_id = " . DB_TABLE_PRODUCT_GROUPS_VALUES . ".id AND product_group_id = " . $product_group_id;
//            $sql = "select name,product_group_value_id from ".DB_TABLE_PRODUCT_GROUPS_VALUES_INFO." where name in (";
//            $in = "";
//            $in = "'" . join("','", array_values($group_values)) . "'";
//            $in .= ")";
//            $sql .= $in . " and product_group_value_id in (select id from".DB_TABLE_PRODUCT_GROUPS_VALUES." where product_group_id=%d)";
//            $sql = u_utils::builderSQL($sql, array($product_group_id));
                $result = database::fetch_full(database::query($sql));
                $group_value_ids = array();//$result['product_group_value_id'];
                $value_names = array();//$result['name'];
                foreach ($result as $row) {
                    $group_value_ids [] = $row['product_group_value_id'];
                    $value_names [] = $row['name'];
                }
                $diff = array_diff($group_values, $value_names);//寻找差值，如果result里没有，则添加
                // 得到的是一个有一个元素，但元素为空的数组，过滤一下
                $diff = array_filter($diff);
                if (!empty($diff)) {// 这里 如果没有不同的，
                    //添加group子项到lc_product_groups_values
                    foreach ($diff as $value_name) {
                        $date = u_utils::getYMDHISDate();
                        $sql = "insert into %s (product_group_id,date_updated,date_created) values(%d,'%s','%s')";
                        $sql = u_utils::builderSQL($sql,
                            array(DB_TABLE_PRODUCT_GROUPS_VALUES, $product_group_id, $date, $date));
                        $result = database::query($sql);
                        $group_value_id = database::insert_id();
                        // 添加group子项lc_product_groups_values_info
                        $sql = "insert into %s (`product_group_value_id`,`language_code`,`name`) values(%d,'en','%s')";
                        $sql = u_utils::builderSQL($sql, array(DB_TABLE_PRODUCT_GROUPS_VALUES_INFO,
                            $group_value_id, $value_name));
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
                $category_name = u_utils::getArrayIndexValue($category_name_other_level_one,$i);
                $category_description = u_utils::getArrayIndexValue($category_descriptions_other_level_one,$i);
                $category_short_description = u_utils::getArrayIndexValue($category_short_descriptions_other_level_one,$i);
                $category_meta_description = u_utils::getArrayIndexValue($category_meta_descriptions_other_level_one,$i);
                $category_head_title = u_utils::getArrayIndexValue($category_head_titles_other_level_one,$i);
                $category_h1_title = u_utils::getArrayIndexValue($category_h1_titles_other_level_one,$i);
                insertOrUpdateSimpleCategory($product_info, $category_name, $parent_id, $category_description, $category_short_description,
                    $category_meta_description, $category_head_title, $category_h1_title, false);
            }
            //添加多级分类数据及关系
            $parent_id = 0;
            for ($i = 0; $i < count($categorie_names); $i++) {
                $category_name = u_utils::getArrayIndexValue($categorie_names,$i);
                $category_description = u_utils::getArrayIndexValue($category_descriptions,$i);
                $category_short_description = u_utils::getArrayIndexValue($category_short_descriptions,$i);
                $category_meta_description = u_utils::getArrayIndexValue($category_meta_descriptions,$i);
                $category_head_title = u_utils::getArrayIndexValue($category_head_titles,$i);
                $category_h1_title = u_utils::getArrayIndexValue($category_h1_titles,$i);
                insertOrUpdateSimpleCategory($product_info, $category_name, $parent_id, $category_description, $category_short_description,
                        $category_meta_description, $category_head_title, $category_h1_title);
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
        if(!empty($category_name)) {//如果category_name不为空，才进行数据操作，否则不进行任何操作
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
                if (isset($category_id) && !empty($category_id)) {
                    $parent_id = $category_id;//改变父类id
                }
                // 新增数据到lc_categories_info表。先测试通过，后期再看如何优化。
                $sql = "insert into ". DB_TABLE_CATEGORIES_INFO ." (category_id, language_code, name, description, short_description, meta_description, head_title, h1_title) 
                  values(%d,'%s','%s','%s','%s','%s','%s','%s')";
                $sql = u_utils::builderSQL($sql, array($category_id, 'en',
                    $category_name, $category_description, $category_short_description, $category_meta_description,
                    $category_head_title, $category_h1_title));
                database::query($sql);
                echo 'Creating new category: ' . $category_name . PHP_EOL;
                //--------------- 更新 default_category_id 同时更新 lc_products_to_categories
                // 1. 检查是否存在product_id 和 category_id 数据存在
                $sql = "select product_id from ".DB_TABLE_PRODUCTS_TO_CATEGORIES." where product_id=%d and category_id=%d";
                $sql = u_utils::builderSQL($sql, array($product_id, $category_id));
                $result = database::fetch(database::query($sql));
                if (empty($result)) {// 添加分类和商品的关联 lc_products_to_categories表
                    $sql = "INSERT INTO ".DB_TABLE_PRODUCTS_TO_CATEGORIES." (product_id, category_id) VALUES (%d, %d)";
                    $sql = u_utils::builderSQL($sql, array($product_id, $category_id));
                    $result = database::query($sql);
                }
            } else {
                if ($isTree == true) {//如果是添加有层级关系的分类，则父类id等于当前找到的id。
                    $parent_id = $category_id;
                } else {//如果是添加平级关系的分类，则父类id为当前的父类id
                    $parent_id = $category_id;
                }
                // update..  lc_categories_info
                updateCategoryInfo($category_id, $category_description, $category_short_description,
                    $category_meta_description, $category_head_title, $category_h1_title);

                echo "Updated  category :" . $category_name . " default_category_id:" . $product_info['default_category_id'] . PHP_EOL;
            }
            $product_info['default_category_id'] = $category_id;// seting default_category_id for product.
        }
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
    function updateProductOther($product_info)
    {

        $product_groups = $product_info['product_groups'];
        $product_id = $product_info['id'];
        $price = $product_info['price'];
        // 判断$product_groups 是否最后一个是逗号，如果是则截取，不是则不处理。
        $lastIndex = strripos($product_groups,",");//拿到索引0开始
        $count = strlen($product_groups);//拿到长度,1开始，所以会比index大1.
        if($lastIndex === $count -1 ) {
            $product_groups = substr($product_groups,0,-1);//去除逗号
        }
        //1. Update products.default_category_id,product_groups field
        $sql = "update ". DB_TABLE_PRODUCTS ." set default_category_id=%d,product_groups='%s' where id=%d";
        $sql = u_utils::builderSQL($sql, array($product_info['default_category_id'],
            $product_groups, $product_info['id']));
        database::query($sql);
        //2. Update lc_products_prices table;
        $sql = "select id from " . DB_TABLE_PRODUCTS_PRICES . " where product_id=" . $product_id;
        $result = database::fetch(database::query($sql));
        if (empty($result)) {
            $sql = "insert into " . DB_TABLE_PRODUCTS_PRICES . " (product_id,USD,EUR) values(%d,%.4f,0.000)";
            $sql = u_utils::builderSQL($sql, array($product_id, $price));
            $result = database::query($sql);
        } else {
            $sql = "update " . DB_TABLE_PRODUCTS_PRICES . "set USD=%.4f where product_id=%d";
            $sql = u_utils::builderSQL($sql, array($price, $product_id));
            $result = database::query($sql);
        }
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
            importProducts($csv);
            exit;
        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }


    function md5Product($product_info)
    {
        //name,short_description,description,attributes,head_title,meta_description
        $md5_str = $product_info['name'] . $product_info['short_description'] . $product_info['description']
            . $product_info['attributes'] . $product_info['head_title'] . $product_info['meta_description'];

        return md5($md5_str);
    }

    // import or export run.
    if (isset($_POST['import_products'])) {
        importCategoriesAndProducts();
    } elseif (isset($_POST['export_products'])) {
        //$csv_array = builderExportCSVArray();
        //exportCategoriesAndProducts();
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