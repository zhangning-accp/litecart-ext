<?php
    if (empty($_GET['category_id'])) $_GET['category_id'] = 0;
    // TODO: 这里是category 首页判断POST里是否有Enabled、Disabled和Online only的地方
    if (isset($_POST['enable']) || isset($_POST['disable']) || isset($_POST['online_only'])) {
        try {
            if (empty($_POST['categories']) && empty($_POST['products'])) {
                throw new Exception(language::translate('error_must_select_categories_or_products', 'You must select categories or products'));
            }

            if (!empty($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_id) {
                    $category = new ctrl_category($category_id);
                    $category->data['status'] = !empty($_POST['enable']) ? 1 : 0;
                    $category->save();
                }
            }

            if (!empty($_POST['products'])) {
                foreach ($_POST['products'] as $product_id) {
                    $product = new ctrl_product($product_id);
                    // TODO: 这是是改变状态的地方
                    $product_status = 0;
                    if (!empty($_POST['enable'])) {
                        $product_status = 1;
                    } else if (!empty($_POST['disable'])) {
                        $product_status = 0;
                    } else if (!empty($_POST['online_only'])) {
                        $product_status = 2;
                    }
                    $product->data['status'] = $product_status;
                    $product->save();
                }
            }

            notices::add('success', language::translate('success_changes_saved', 'Changes saved successfully'));
            header('Location: ' . document::link());
            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    if (isset($_POST['duplicate'])) {

        try {
            if (!empty($_POST['categories'])) throw new Exception(language::translate('error_cant_duplicate_category', 'You can\'t duplicate a category'));
            if (empty($_POST['products'])) throw new Exception(language::translate('error_must_select_products', 'You must select products'));
            if (empty($_POST['category_id'])) throw new Exception(language::translate('error_must_select_category', 'You must select a category'));

            foreach ($_POST['products'] as $product_id) {
                $original = new ctrl_product($product_id);
                $product = new ctrl_product();

                $product->data = $original->data;
                $product->data['id'] = null;
                $product->data['status'] = 0;
                $product->data['categories'] = array($_POST['category_id']);
                $product->data['image'] = null;
                $product->data['images'] = array();

                foreach (array('campaigns', 'options', 'options_stock') as $field) {
                    if (empty($product->data[$field])) continue;
                    foreach (array_keys($product->data[$field]) as $key) {
                        $product->data[$field][$key]['id'] = null;
                    }
                }

                if (!empty($original->data['images'])) {
                    foreach ($original->data['images'] as $image) {
                        $product->add_image(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $image['filename']);
                    }
                }

                foreach (array_keys($product->data['name']) as $language_code) {
                    $product->data['name'][$language_code] .= ' (copy)';
                }

                $product->data['status'] = 0;
                $product->save();
            }

            notices::add('success', sprintf(language::translate('success_duplicated_d_products', 'Duplicated %d products'), count($_POST['products'])));
            header('Location: ' . document::link('', array('category_id' => $_POST['category_id']), true));
            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    if (isset($_POST['copy'])) {

        try {
            if (!empty($_POST['categories'])) throw new Exception(language::translate('error_cant_copy_category', 'You can\'t copy a category'));
            if (empty($_POST['products'])) throw new Exception(language::translate('error_must_select_products', 'You must select products'));
            if (isset($_POST['category_id']) && $_POST['category_id'] == '') throw new Exception(language::translate('error_must_select_category', 'You must select a category'));

            foreach ($_POST['products'] as $product_id) {
                $product = new ctrl_product($product_id);
                $product->data['categories'][] = $_POST['category_id'];
                $product->save();
            }

            notices::add('success', sprintf(language::translate('success_copied_d_products', 'Copied %d products'), count($_POST['products'])));
            header('Location: ' . document::link('', array('category_id' => $_POST['category_id']), true));
            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    if (isset($_POST['move'])) {

        try {
            if (empty($_POST['categories']) && empty($_POST['products'])) throw new Exception(language::translate('error_must_select_category_or_product', 'You must select a category or product'));
            if (isset($_POST['category_id']) && $_POST['category_id'] == '') throw new Exception(language::translate('error_must_select_category', 'You must select a category'));
            if (isset($_POST['category_id']) && isset($_POST['categories']) && in_array($_POST['category_id'], $_POST['categories'])) throw new Exception(language::translate('error_cant_move_category_to_itself', 'You can\'t move a category to itself'));

            if (isset($_POST['category_id']) && isset($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_id) {
                    if (in_array($_POST['category_id'], array_keys(functions::catalog_category_descendants($category_id)))) {
                        throw new Exception(language::translate('error_cant_move_category_to_descendant', 'You can\'t move a category to a descendant'));
                        break;
                    }
                }
            }

            if (!empty($_POST['products'])) {
                foreach ($_POST['products'] as $product_id) {
                    $product = new ctrl_product($product_id);
                    $product->data['categories'] = array($_POST['category_id']);
                    $product->save();
                }
                notices::add('success', sprintf(language::translate('success_moved_d_products', 'Moved %d products'), count($_POST['products'])));
            }

            if (!empty($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_id) {
                    $category = new ctrl_category($category_id);
                    $category->data['parent_id'] = $_POST['category_id'];
                    $category->save();
                }
                notices::add('success', sprintf(language::translate('success_moved_d_categories', 'Moved %d categories'), count($_POST['categories'])));
            }

            header('Location: ' . document::link('', array('category_id' => $_POST['category_id']), true));
            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    if (isset($_POST['unmount'])) {

        try {
            if (empty($_POST['categories']) && empty($_POST['products'])) throw new Exception(language::translate('error_must_select_category_or_product', 'You must select a category or product'));
            if (empty($_GET['category_id'])) throw new Exception(language::translate('error_category_must_be_nested_in_another_category_to_unmount', 'A category must be nested in another category to be unmounted'));

            if (!empty($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_id) {
                    $category = new ctrl_category($category_id);
                    if ($category->data['parent_id'] == $_GET['category_id']) {
                        $category->data['parent_id'] = 0;
                        $category->save();
                    }
                }
                notices::add('success', sprintf(language::translate('success_unmounted_d_categories', 'Unmounted %d categories'), count($_POST['categories'])));
            }

            if (!empty($_POST['products'])) {
                foreach ($_POST['products'] as $product_id) {
                    $product = new ctrl_product($product_id);
                    foreach (array_keys($product->data['categories']) as $key) {
                        if ($product->data['categories'][$key] == $_GET['category_id']) {
                            unset($product->data['categories'][$key]);
                            $product->save();
                        }
                    }
                }
                notices::add('success', sprintf(language::translate('success_unmounted_d_products', 'Unmounted %d products'), count($_POST['products'])));
            }

            if (isset($_POST['categories']) && in_array($_GET['category_id'], $_POST['categories'])) unset($_GET['category_id']);

            header('Location: ' . document::link('', array(), true));
            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }

    if (isset($_POST['delete'])) {

        try {
            if (!empty($_POST['categories'])) throw new Exception(language::translate('error_only_products_are_supported', 'Only products are supported for this operation'));
            if (empty($_POST['products'])) throw new Exception(language::translate('error_must_select_products', 'You must select products'));

            foreach ($_POST['products'] as $product_id) {
                $product = new ctrl_product($product_id);
                $product->delete();
            }

            notices::add('success', sprintf(language::translate('success_deleted_d_products', 'Deleted %d products'), count($_POST['products'])));
            header('Location: ' . document::link());
            exit;

        } catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
    }
?>
    <ul class="list-inline pull-right">
        <li><?php echo functions::form_draw_form_begin('search_form', 'get', '', false, 'onsubmit="return false;"') . functions::form_draw_search_field('query', true, 'placeholder="' . language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword') . '"  onkeydown=" if (event.keyCode == 13) location=(\'' . document::link('', array(), true, array('page', 'query')) . '&query=\' + encodeURIComponent(this.value))"') . functions::form_draw_form_end(); ?></li>
        <li><?php echo functions::form_draw_link_button(document::link('', array('app' => $_GET['app'], 'doc' => 'edit_category', 'parent_id' => $_GET['category_id'])), language::translate('title_add_new_category', 'Add New Category'), '', 'add'); ?></li>
        <li><?php echo functions::form_draw_link_button(document::link('', array('app' => $_GET['app'], 'doc' => 'edit_product'), array('category_id')), language::translate('title_add_new_product', 'Add New Product'), '', 'add'); ?></li>
    </ul>

    <h1><?php echo $app_icon; ?><?php echo language::translate('title_catalog', 'Catalog'); ?></h1>

<?php echo functions::form_draw_form_begin('catalog_form', 'post'); ?>

    <table class="table table-striped table-hover data-table">
        <thead>
        <tr>
            <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle', 'data-toggle="checkbox-toggle"'); ?></th>
            <th>&nbsp;</th>
            <th class="main"><?php echo language::translate('title_name', 'Name'); ?></th>
            <th></th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $num_category_rows = 0;
            $num_product_rows = 0;

            if (!empty($_GET['query'])) {

            $code_regex = functions::format_regex_code($_GET['query']);

            $products_query = database::query(
                "select p.*, pi.name
      from " . DB_TABLE_PRODUCTS . " p
      left join " . DB_TABLE_PRODUCTS_INFO . " pi on (pi.product_id = p.id and pi.language_code = '" . language::$selected['code'] . "')
      left join " . DB_TABLE_MANUFACTURERS . " m on (p.manufacturer_id = m.id)
      left join " . DB_TABLE_SUPPLIERS . " s on (p.supplier_id = s.id)
      where (
        p.id = '" . database::input($_GET['query']) . "'
        or pi.name like '%" . database::input($_GET['query']) . "%'
        or p.code regexp '" . database::input($code_regex) . "'
        or p.sku regexp '" . database::input($code_regex) . "'
        or p.mpn regexp '" . database::input($code_regex) . "'
        or p.gtin regexp '" . database::input($code_regex) . "'
        or pi.short_description like '%" . database::input($_GET['query']) . "%'
        or pi.description like '%" . database::input($_GET['query']) . "%'
        or m.name like '%" . database::input($_GET['query']) . "%'
        or s.name like '%" . database::input($_GET['query']) . "%'
      )
      order by pi.name asc;"
            );

            if (database::num_rows($products_query) > 0) {
                while ($product = database::fetch($products_query)) {
                    $num_product_rows++;
                    ?>
                    <tr class="<?php echo empty($product['status']) ? 'semi-transparent' : null; ?>">
                        <td><?php echo functions::form_draw_checkbox('products[' . $product['id'] . ']', $product['id']); ?></td>
                        <td><?php echo functions::draw_fonticon('fa-circle', 'style="color: ' . (!empty($product['status']) ? '#88cc44' : '#ff6644') . ';"'); ?></td>
                        <td><?php echo '<img src="' . functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product['image'], 16, 16, 'FIT_USE_WHITESPACING') . '" alt="" style="width: 16px; height: 16px; vertical-align: bottom;" />'; ?>
                            <a href="<?php echo document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_product', 'product_id' => $product['id'])); ?>"> <?php echo $product['name']; ?></a>
                        </td>
                        <td></td>
                        <td class="text-right"><a
                                    href="<?php echo document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_product', 'product_id' => $product['id'])); ?>"
                                    title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a>
                        </td>
                    </tr>
                    <?php
                }
            }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5"><?php echo language::translate('title_products', 'Products'); ?>
                : <?php echo $num_product_rows; ?></td>
        </tr>
        </tfoot>
        <?php

            } else {

            $category_trail = functions::catalog_category_trail($_GET['category_id']);
            if (!empty($category_trail)) {
                $category_trail = array_keys($category_trail);
            } else {
                $category_trail = array();
            }

            function admin_catalog_category_tree($category_id = 0, $depth = 1)
            {
                global $category_trail, $rowclass, $num_category_rows;

                $output = '';

                if (empty($category_id)) {
                    $output .= '<tr>' . PHP_EOL
                        . '  <td></td>' . PHP_EOL
                        . '  <td></td>' . PHP_EOL
                        . '  <td>' . functions::draw_fonticon('fa-folder-open', 'style="color: #cccc66;"') . ' <strong><a href="' . document::href_link('', array('category_id' => '0'), true) . '">[' . language::translate('title_root', 'Root') . ']</a></strong></td>' . PHP_EOL
                        . '  <td>&nbsp;</td>' . PHP_EOL
                        . '  <td>&nbsp;</td>' . PHP_EOL
                        . '</tr>' . PHP_EOL;
                }

                // Output subcategories
                $categories_query = database::query(
                    "select c.id, c.status, ci.name
        from " . DB_TABLE_CATEGORIES . " c
        left join " . DB_TABLE_CATEGORIES_INFO . " ci on (ci.category_id = c.id and ci.language_code = '" . language::$selected['code'] . "')
        where c.parent_id = '" . (int)$category_id . "'
        order by c.priority asc, ci.name asc;"
                );

                while ($category = database::fetch($categories_query)) {
                    $num_category_rows++;

                    $output .= '<tr class="' . (!$category['status'] ? ' semi-transparent' : null) . '">' . PHP_EOL
                        . '  <td>' . functions::form_draw_checkbox('categories[' . $category['id'] . ']', $category['id'], true) . '</td>' . PHP_EOL
                        . '  <td>' . functions::draw_fonticon('fa-circle', 'style="color: ' . (!empty($category['status']) ? '#88cc44' : '#ff6644') . ';"') . '</td>' . PHP_EOL;
                    if (@in_array($category['id'], $category_trail)) {
                        $output .= '  <td>' . functions::draw_fonticon('fa-folder-open', 'style="color: #cccc66; margin-left: ' . ($depth * 16) . 'px;"') . ' <strong><a href="' . document::href_link('', array('category_id' => $category['id']), true) . '">' . ($category['name'] ? $category['name'] : '[untitled]') . '</a></strong></td>' . PHP_EOL;
                    } else {
                        $output .= '  <td>' . functions::draw_fonticon('fa-folder', 'style="color: #cccc66; margin-left: ' . ($depth * 16) . 'px;"') . ' <a href="' . document::href_link('', array('category_id' => $category['id']), true) . '">' . ($category['name'] ? $category['name'] : '[untitled]') . '</a></td>' . PHP_EOL;
                    }
                    $output .= '  <td>&nbsp;</td>' . PHP_EOL
                        . '  <td class="text-right"><a href="' . document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_category', 'category_id' => $category['id'])) . '" title="' . language::translate('title_edit', 'Edit') . '">' . functions::draw_fonticon('fa-pencil') . '</a></td>' . PHP_EOL
                        . '</tr>' . PHP_EOL;

                    if (in_array($category['id'], $category_trail)) {

                        if (database::num_rows(database::query("select id from " . DB_TABLE_CATEGORIES . " where parent_id = '" . (int)$category['id'] . "' limit 1;")) > 0
                            || database::fetch(database::query("select category_id from " . DB_TABLE_PRODUCTS_TO_CATEGORIES . " where category_id = " . (int)$category['id'] . " limit 1;")) > 0
                        ) {
                            $output .= admin_catalog_category_tree($category['id'], $depth + 1);

                            // Output products
                            if (in_array($category['id'], $category_trail)) {
                                $output .= admin_catalog_category_products($category['id'], $depth + 1);
                            }

                        } else {

                            $output .= '<tr>' . PHP_EOL
                                . '  <td>&nbsp;</td>' . PHP_EOL
                                . '  <td>&nbsp;</td>' . PHP_EOL
                                . '  <td><em style="margin-left: ' . (($depth + 1) * 16) . 'px;">' . language::translate('title_empty', 'Empty') . '</em></td>' . PHP_EOL
                                . '  <td>&nbsp;</td>' . PHP_EOL
                                . '  <td>&nbsp;</td>' . PHP_EOL
                                . '</tr>' . PHP_EOL;
                        }
                    }
                }

                database::free($categories_query);

                // Output products
                if (empty($category_id)) {
                    $output .= admin_catalog_category_products($category_id, $depth);
                }

                return $output;
            }

            function admin_catalog_category_products($category_id = 0, $depth = 1)
            {
                global $num_product_rows;

                $output = '';
                // TODO:查询当前分类下的产品信息
                $products_query = database::query(
                    "select p.id, p.status, p.image, pi.name, p2c.category_id from " . DB_TABLE_PRODUCTS . " p
        left join " . DB_TABLE_PRODUCTS_INFO . " pi on (pi.product_id = p.id and pi.language_code = '" . language::$selected['code'] . "')
        left join " . DB_TABLE_PRODUCTS_TO_CATEGORIES . " p2c on (p2c.product_id = p.id)
        where " . (!empty($category_id) ? "p2c.category_id = " . (int)$category_id : "(p2c.category_id is null or p2c.category_id = 0)") . "
        group by p.id
        order by pi.name asc;"
                );

                $display_images = true;
                if (database::num_rows($products_query) > 100) {
                    $display_images = false;
                }

                while ($product = database::fetch($products_query)) {
                    $num_product_rows++;
                    //TODO: 后台分类下的商品，按状态附不同的color。
                    $product_status_style = "";
                    switch ($product['status']) {
                        case 0:// 下架 Enabled
                            $product_status_style = "#a9a9a9";
                            break;
                        case 1:// 上架 Disabled
                            $product_status_style = "#347bd9";
                            break;
                        case 2://隐藏 Online only
                            $product_status_style = "#d0cb2b";
                            break;
                    }
                    $output .= '<tr class="' . (!$product['status'] ? ' semi-transparent' : null) . '">' . PHP_EOL
                        . '  <td>' . functions::form_draw_checkbox('products[' . $product['id'] . ']', $product['id'], true) . '</td>' . PHP_EOL
                        . '  <td>' . functions::draw_fonticon('fa-circle', 'style="color: ' . $product_status_style . ';"') . '</td>' . PHP_EOL;
                    if ($display_images) {
                        $output .= '  <td><img src="' . functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product['image'], 16, 16, 'FIT_USE_WHITESPACING') . '" style="margin-left: ' . ($depth * 16) . 'px; width: 16px; height: 16px; vertical-align: bottom;" /> 
            <a href="' . document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_product', 'category_id' => $category_id, 'product_id' => $product['id'])) . '" style="color:' . $product_status_style . '">' . ($product['name'] ? $product['name'] : '[untitled]') . '</a></td>' . PHP_EOL;
                    } else {
                        $output .= '  <td><span style="margin-left: ' . (($depth + 1) * 16) . 'px;">&nbsp;<a href="' . document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_product', 'category_id' => $category_id, 'product_id' => $product['id'])) . '">' . $product['name'] . '</a></span></td>' . PHP_EOL;
                    }

                    $output .= '  <td style="text-align: right;"></td>' . PHP_EOL
                        . '  <td class="text-right"><a href="' . document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_product', 'category_id' => $category_id, 'product_id' => $product['id'])) . '" title="' . language::translate('title_edit', 'Edit') . '">' . functions::draw_fonticon('fa-pencil') . '</a></td>' . PHP_EOL
                        . '</tr>' . PHP_EOL;
                }
                database::free($products_query);

                return $output;
            }

            echo admin_catalog_category_tree();
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5"><?php echo language::translate('title_categories', 'Categories'); ?>
                    : <?php echo $num_category_rows; ?>
                    , <?php echo language::translate('title_products', 'Products'); ?>
                    : <?php echo $num_product_rows; ?></td>
            </tr>
            </tfoot>
            <?php
        }
        ?>
    </table>

    <p>
    <ul class="list-inline">
        <li><?php echo language::translate('text_with_selected', 'With selected'); ?>:</li>
        <li>
            <div class="btn-group"><?php //TODO: 分类首页底部Enable、Disable、Online only 选项?>
                <?php echo functions::form_draw_button('enable', language::translate('title_enable', 'Enable'), 'submit', '', 'on'); ?>
                <?php echo functions::form_draw_button('disable', language::translate('title_disable', 'Disable'), 'submit', '', 'off'); ?>
                <?php echo functions::form_draw_button('online_only', language::translate('title_online', '2'), 'submit', '', 'off'); ?>
            </div>
        </li>
        <li>
            <div>
                <?php echo functions::form_draw_categories_list('category_id', isset($_POST['category_id']) ? $_POST['category_id'] : ''); ?>
            </div>
        </li>
        <li>
            <div class="btn-group">
                <?php echo functions::form_draw_button('move', language::translate('title_move', 'Move'), 'submit', 'onclick="if (!confirm(\'' . str_replace("'", "\\\'", language::translate('warning_mounting_points_will_be_replaced', 'Warning: All current mounting points will be replaced.')) . '\')) return false;"'); ?>
                <?php echo functions::form_draw_button('copy', language::translate('title_copy', 'Copy'), 'submit'); ?>
                <?php echo functions::form_draw_button('duplicate', language::translate('title_duplicate', 'Duplicate'), 'submit'); ?>
            </div>
        </li>
        <li>
            <div class="btn-group">
                <?php echo functions::form_draw_button('unmount', language::translate('title_unmount', 'Unmount'), 'submit'); ?>
                <?php echo functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!confirm(\'' . str_replace("'", "\\\'", language::translate('text_are_you_sure', 'Are you sure?')) . '\')) return false;"'); ?>
            </div>
        </li>
    </ul>
    </p>

<?php echo functions::form_draw_form_end(); ?>