<?php
    // catalog->CSV Import/Export page and process import/export Categories or Products
    /**-----------------------------------------------------------
     * 导入功能的使用场景描述:
     * 1. 新增商品
     * 2. 下架商品  sku
     * 3. 修改库存  sku
     * 4. 更新商品(更新商品时有小机率会更新分类) sku
     */

    /**----------------------------------------------------------
     * 导入的数据项
     *

     *
     *
     *
     *
     */
    /**
     * 导入分类
     * @param $csv csv文件
     * @param $isInsertNew 当数据不存在时是否插入新数据。如果true，当数据库找不到导入的数据时，则将新增数据到数据库.
     * 该方法测试通过。 2018-9-16 11:30
     */
    function importCategories($csv)
    {
        $line = 0;
        foreach ($csv as $row) {//遍历csv文件
            $line++;
            $categorie_names = $row['categorie_names'];//"Rubber Ducks|Subcategory|shot,d,d,d";
            $category_descriptions = $row['category_descriptions'];// [|]ddddd[|]|[|]dssdfdf[|], 注：如果某些分类没有title，则用一个空格 | | | , ,
            $category_short_descriptions = $row['category_short_descriptions'];
            $category_meta_descriptions = $row['category_meta_descriptions'];
            $category_head_titles = $row['category_head_titles'];
            $category_h1_titles = $row['category_h1_titles'];

            //示例： ["Collectibles","Barware","Shot Glasses,man,woman"]
            $categorie_names = preg_split("/[|]/", $categorie_names);
            //["Collectibles","Barware","Shot Glasses,man,woman"]
            $categorie_names = array_filter($categorie_names);
            //"Shot Glasses,man,woman"
            $category_name_tmp = array_pop($categorie_names);
            //["Shot Glasses","man","woman"]
            $category_name_tmp = findFirstCategory($category_name_tmp,"/,/");
            //将第一个分类放到原数组。["Collectibles","Barware","Shot Glasses"]
            $categorie_names[] = array_shift($category_name_tmp);

            $category_descriptions = preg_split("/\[\|\]/", $category_descriptions);
            $category_descriptions = array_filter($category_descriptions);
            $category_descriptions_tmp = array_pop($category_descriptions);
            $category_descriptions_tmp = findFirstCategory($category_descriptions_tmp,"/\[,\]/");
            $category_descriptions[] = array_shift($category_descriptions_tmp);

            $category_short_descriptions = preg_split("/\[\|\]/", $category_short_descriptions);
            $category_short_descriptions = array_filter($category_short_descriptions);
            $category_short_descriptions_tmp = array_pop($category_short_descriptions);
            $category_short_descriptions_tmp = findFirstCategory($category_short_descriptions_tmp,"/\[,\]/");
            $category_short_descriptions[] = array_shift($category_short_descriptions_tmp);

            $category_meta_descriptions = preg_split("/\[\|\]/", $category_meta_descriptions);
            $category_meta_descriptions = array_filter($category_meta_descriptions);
            $category_meta_descriptions_tmp = array_pop($category_meta_descriptions);
            $category_meta_descriptions_tmp = findFirstCategory($category_meta_descriptions_tmp,"/\[,\]/");
            $category_meta_descriptions[] = array_shift($category_meta_descriptions_tmp);

            $category_head_titles = preg_split("/\[\|\]/", $category_head_titles);
            $category_head_titles = array_filter($category_head_titles);
            $category_head_titles_tmp = array_pop($category_head_titles);// 拿到最后一个
            $category_head_titles_tmp = findFirstCategory($category_head_titles_tmp,"/\[,\]/");
            $category_head_titles[] = array_shift($category_head_titles_tmp);// 拿到第一个

            $category_h1_titles = preg_split("/\[\|\]/", $category_h1_titles);
            $category_h1_titles = array_filter($category_h1_titles);
            $category_h1_titles_tmp = array_pop($category_h1_titles);
            $category_h1_titles_tmp = findFirstCategory($category_h1_titles_tmp,"/\[,\]/");
            $category_h1_titles[] = array_shift($category_h1_titles_tmp);
            //检查各数组长度是否一致
            if (count($categorie_names) !== count($category_descriptions)
                && count($categorie_names) !== count($category_meta_descriptions)
                && count($categorie_names) !== count($category_short_descriptions)
                && count($categorie_names) !== count($category_head_titles)
                && count($categorie_names) !== count($category_h1_titles)
            ) {
                echo "categorie_names, category_descriptions, category_short_descriptions, category_meta_descriptions, 
                    category_head_titles, category_h1_titles column count doesn't match. Please check !";
                exit;
            } else {
                $parent_id = 0;
                //添加分类里摘出的一级分类
                for($i = 0; $i < count($category_name_tmp);$i ++) {
                    $parent_id = 0;
                    $category_name = $category_name_tmp[$i];
                    $category_description = $category_descriptions_tmp[$i];
                    $category_short_description = $category_short_descriptions_tmp[$i];
                    $category_meta_description = $category_meta_descriptions_tmp[$i];
                    $category_head_title = $category_head_titles_tmp[$i];
                    $category_h1_title = $category_h1_titles_tmp[$i];
                    insertOrUpdateCategory($category_name,$parent_id,$category_description,$category_short_description,
                        $category_meta_description,$category_head_title,$category_h1_title,false);
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
                    insertOrUpdateCategory($category_name,$parent_id,$category_description,$category_short_description,
                            $category_meta_description,$category_head_title,$category_h1_title);
                }
            }
        }
    }// import_categories end.

    function findFirstCategory($category_name_tmp,$pattern) {
        $category_name_tmp = preg_split($pattern, $category_name_tmp);
        $category_name_tmp = array_filter($category_name_tmp);
        return $category_name_tmp;
    }

    /**
     * 添加分类，该方法会构建层级结构
     * @param $category_name     分类名
     * @param $parent_id        分类的父类
     * @param $category_description     分类的详细描述
     * @param $category_short_description   分类的简介
     * @param $category_meta_description    分类meta描述
     * @param $category_head_title  分类的<head>标签里的内容
     * @param $category_h1_title    分类在页面上显示的标题
     * @param $isTree   是否为添加层级结构。
     */
    function insertOrUpdateCategory($category_name,&$parent_id,$category_description,$category_short_description,
                                    $category_meta_description,$category_head_title,$category_h1_title,$isTree = true)
    {
        // 查找每个分类数据，如果找到，则跳过，如果没找到，则添加
        $sql = "SELECT id,parent_id FROM %s WHERE id = (SELECT category_id FROM %s WHERE NAME = '%s' limit 1) and parent_id = %d limit 1";
        $sql = u_utils::builderSQL($sql,array(DB_TABLE_CATEGORIES, DB_TABLE_CATEGORIES_INFO,$category_name,$parent_id));
        $result = database::fetch(database::query($sql));
        if (empty($result)) {//记录不存在
            // 1. 插入新的分类到lc_categories，插入的数据有：langunge_code,date_created,插入完成后拿到id值。

            $sql = "insert into " . DB_TABLE_CATEGORIES
                . " (parent_id, dock, status, list_style, date_created) values (%d,'%s',%d,'%s','%s')";
            $sql = u_utils::builderSQL($sql, array($parent_id, 'tree', 1, 'rows', date('Y-m-d H:i:s')));
            $result = database::query($sql);
            $id = database::insert_id();//query("select max(id) from " . DB_TABLE_CATEGORIES);//2. 拿到刚才添加的id。
            // 如果$id不为null且不是""，这设置parent_id
            if (isset($id) && !empty($id)) {
                $parent_id = $id;//改变父类id
            }
            // 新增数据到lc_categories_info表。先测试通过，后期再看如何优化。
            $sql = "insert into %s (category_id, language_code, name, description, short_description, meta_description, head_title, h1_title) 
                  values(%d,'%s','%s','%s','%s','%s','%s','%s')";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_CATEGORIES_INFO, $id, 'en',
                $category_name, $category_description, $category_short_description,$category_meta_description,
                $category_head_title, $category_h1_title));
            database::query($sql);
            echo 'Creating new category: ' . $category_name . PHP_EOL;
        } else {// update..  lc_categories_info

            if ($isTree == true) {//如果是添加有层级关系的分类，则父类id等于当前找到的id。
                $parent_id = $result['id'];
            } else {//如果是添加平级关系的分类，则父类id为当前的父类id
                $parent_id = $result['parent_id'];
            }
            updateCategory($result['id'],$category_description,$category_short_description,
                $category_meta_description,$category_head_title,$category_h1_title);
            echo "Updated  category :" . $category_name . "\r\n";
        }
    }

    function updateCategory($category_id,$category_description,$category_short_description,$category_meta_description,$category_head_title,$category_h1_title) {
        $sql = "update %s SET description = '%s', short_description = '%s',
                meta_description = '%s', head_title = '%s',h1_title = '%s' WHERE category_id = %d";
        $sql = u_utils::builderSQL($sql,array(DB_TABLE_CATEGORIES_INFO,$category_description,$category_short_description,
            $category_meta_description,$category_head_title,$category_h1_title,$category_id));
        $result = database::query($sql);
    }

    /**
     * 导入产品相关数据
     * @param $csv 产品csv数据
     */
    function importProducts($csv)
    {
    /*------------------------------------------------------------------
    code	* (唯一号，可用来在sku外的场合用)
	sku	*
	name	*
	short_description	*
	description	*
	attributes	*
	head_title	*
	meta_description	*
	images	* (url,不支持本地图片)
	purchase_price	*
	price	*
	quantity	*
	option_groups	*  	- 新增  option_name:n1  理出关联表
		`lc_option_groups`
		`lc_option_groups_info`
		`lc_option_values`
		`lc_option_values_info`
	product_goups  *	- 新增 grop_name:n1,n2,n3... 理出关联表
		`lc_product_groups`
		`lc_product_groups_info`
		`lc_product_groups_values`
		`lc_product_groups_values_info`

    逻辑梳理：
            1. 如果有id则是update，如果没有id则为insert
            2. 首先处理option_groups和product_groups
            3.
            4.
     */

        $line = 0;
        foreach ($csv as $row) {//遍历csv文件
            $option_groups_str = $row['option_groups'];
            //导入option-groups 初步测试通过。2018-09-16 22:25
            importOptionGroups($option_groups_str);
        }
    }

    function importOptionGroups($option_groups_str)
    {
        //option_groups 数据格式:   option_name(选项父类名):select_name(选项子类值)
        $option_groups = preg_split("/:/",$option_groups_str);
        importOptionGroup($option_groups[0],$option_groups[1]);
    }
    function importOptionGroup($option_name,$option_value)
    {
        // 查找是否存在该数据选项，如果不存在，则添加，存在则不做处理
        //1. 需要查找lc_option_groups_info和lc_option_values_info。
        //类型是textarea和input的选项数据是放在lc_option_values表里。暂时不考虑这两种类型
        // 其它类型的选项数据是放在lc_option_values_info表里
        $sql = "select id,group_id from %s where name = '%s'";
        $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_GROUPS_INFO, $option_name));
        $result = database::fetch(database::query($sql));
        $group_id = $result['group_id'];
        if (empty($result)) {
            //添加如下数据到对应表：
            //1. `lc_option_groups`
            $sql = "insert into %s (function,required,sort,date_created) VALUES ('%s',1,'%s','%s')";
            $sql = u_utils::builderSQL(DB_TABLE_OPTION_GROUPS,
                array('select', 1, priority, u_utils::builderSQL()));
            $result = database::query($sql);//拿到id值
            $group_id = database::insert_id();
            //`lc_option_groups_info`
            $sql = "insert into %s (group_id,language_code,name,description) VALUES ('%d','en','%s','')";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_GROUPS_INFO, $option_groups_id, $option_name));
            $result = database::query($sql);
            // 添加$option_name 数据结束
        }
        //添加$option_value 开始`lc_option_values`
        // 先查询$option_value在表lc_option_values_info是否存在，如果不存在，则添加，如果存在则忽略
        $sql = "select id from %s where name = '%s'";
        $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES_INFO, $option_value));
        $result = database::fetch(database::query($sql));
        //如果$option_value不存在。
        if (empty($result)) {
            $sql = "insert into %s (group_id,value,priority) VALUES (%d,'',1)";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES, $group_id));
            $result = database::query($sql);
            $value_id = database::insert_id();
            //`lc_option_values_info`
            $sql = "insert into %s (value_id,language_code,name) VALUES (%d,'en','%s')";
            $sql = u_utils::builderSQL($sql, array(DB_TABLE_OPTION_VALUES_INFO, $value_id,$option_value));
            $result = database::query($sql);
        }
    }
    function importProductGroups()
    {
        while(true) {
            importProductGroup();
        }
    }
    function importProductGroup()
    {

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
            importProducts($csv, $isInsertNew);
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

    function builderExportCSVArray()
    {
        $csv_array = array(
            'category_id' => '',
            'category_status' => '',
            'category_parent_id' => '',
            'category_code' => '',
            'category_name' => '',
            'category_keywords' => '',
            'category_short_description' => '',
            'category_description' => '',
            'category_meta_description' => '',
            'category_head_title' => '',
            'category_h1_title' => '',
            'category_image' => '',
            'category_priority' => '',
            'category_language_code' => '',
            'id' => '',
            'status' => '',
            'categories' => '',
            'product_groups' => '',
            'manufacturer_id' => '',
            'supplier_id' => '',
            'code' => '',
            'sku' => '',
            'mpn' => '',
            'gtin' => '',
            'taric' => '',
            'name' => '',
            'short_description' => '',
            'description' => '',
            'keywords' => '',
            'attributes' => '',
            'head_title' => '',
            'meta_description' => '',
            'images' => '',
            'purchase_price' => '',
            'purchase_price_currency_code' => '',
            'price' => '',
            'tax_class_id' => '',
            'quantity' => '',
            'quantity_unit_id' => '',
            'weight' => '',
            'weight_class' => '',
            'delivery_status_id' => '',
            'sold_out_status_id' => '',
            'language_code' => '',
            'currency_code' => '',
            'date_valid_from' => '',
            'date_valid_to' => '',
        );

        return $csv_array;
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