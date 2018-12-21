<?php
    /**
     * 产品弹出层和详情页处理数据的地方。 显示数据的地方在 public_html/includes/templates/default.catalog/views/box_product.inc.php
     */
  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    document::$layout = 'ajax';
    header('X-Robots-Tag: noindex');
  }

  if (!empty($_GET['product_id'])) {
    $product = reference::product($_GET['product_id']);
  }

  if (empty($_GET['category_id']) && empty($product->manufacturer)) {
    if (u_utils::count($product->category_ids)) {
      $category_ids = array_values($product->category_ids);
      $_GET['category_id'] = array_shift($category_ids);
    }
  }

  if (empty($product->id)) {
    http_response_code(410);
    echo language::translate('error_410_gone', 'The requested file is no longer available');
    return;
  }

  if (empty($product->status)) {
    echo language::translate('error_404_not_found', 'The requested file could not be found');
    http_response_code(404);
    return;
  }

  if ($product->date_valid_from > date('Y-m-d H:i:s')) {
    notices::add('errors', sprintf(language::translate('text_product_cannot_be_purchased_until_s', 'The product cannot be purchased until %s'), language::strftime(language::$selected['format_date'], strtotime($product->date_valid_from))));
  }

  if (substr($product->date_valid_to, 0, 10) != '0000-00-00' && substr($product->date_valid_to, 0, 4) > '1971' && $product->date_valid_to < date('Y-m-d H:i:s')) {
    notices::add('errors', language::translate('text_product_can_no_longer_be_purchased', 'The product can no longer be purchased'));
  }

  database::query(
    "update ". DB_TABLE_PRODUCTS ."
    set views = views + 1
    where id = '". (int)$_GET['product_id'] ."'
    limit 1;"
  );

  if (!empty($_GET['category_id'])) {
    foreach (functions::catalog_category_trail($_GET['category_id']) as $category_id => $category_name) {
      document::$snippets['title'][] = $category_name;
    }
  } else if (!empty($product->manufacturer)) {
    document::$snippets['title'][] = $product->manufacturer->name;
  }

  document::$snippets['title'][] = $product->head_title ? $product->head_title : $product->name;
  document::$snippets['description'] = $product->meta_description ? $product->meta_description : strip_tags($product->short_description);
  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('product', array('product_id' => (int)$product->id), false) .'" />';

  if (!empty($product->image)) {
    document::$snippets['head_tags'][] = '<meta property="og:image" content="'. document::link(WS_DIR_IMAGES . $product->image) .'"/>';
  }

  if (!empty($_GET['category_id'])) {
    breadcrumbs::add(language::translate('title_categories', 'Categories'), document::ilink('categories'));
    foreach (functions::catalog_category_trail($_GET['category_id']) as $category_id => $category_name) {
      breadcrumbs::add($category_name, document::ilink('category', array('category_id' => (int)$category_id)));
    }
  } else if (!empty($product->manufacturer)) {
    breadcrumbs::add(language::translate('title_manufacturers', 'Manufacturers'), document::ilink('manufacturers'));
    breadcrumbs::add($product->manufacturer->name, document::ilink('manufacturer', array('manufacturer_id' => $product->manufacturer->id)));
  }
  breadcrumbs::add($product->name);

  functions::draw_lightbox();

// Recently viewed products
  if (isset(session::$data['recently_viewed_products'][$product->id])) {
    unset(session::$data['recently_viewed_products'][$product->id]);
  }

  session::$data['recently_viewed_products'][$product->id] = array(
    'id' => $product->id,
    'name' => $product->name,
    'image' => $product->image,
  );

// Page
  $_page = new view();

  $schema_json = array(
    '@context' => 'http://schema.org/',
    '@type' => 'Product',
    'productID' => $product->id,
    'sku' => $product->sku,
    'gtin14' => $product->gtin,
    'name' => $product->name,
    'image' => document::link(!empty($product->images) ? WS_DIR_IMAGES . @array_shift(array_values($product->images)) : WS_DIR_IMAGES . 'no_image.png'),
    'description' => !empty($product->description) ? strip_tags($product->description) : '',
    'brand' => array(),
    'offers' => array(
      '@type' => 'Offer',
      'priceCurrency' => currency::$selected['code'],
      'price' => (isset($product->campaign['price']) && $product->campaign['price'] > 0) ? tax::get_price($product->campaign['price'], $product->tax_class_id) : tax::get_price($product->price, $product->tax_class_id),
      'priceValidUntil' => (!empty($product->campaign) && strtotime($product->campaign['end_date']) > time()) ? $product->campaign['end_date'] : null,
      //'itemCondition' => 'http://schema.org/UsedCondition',
      //'availability' => 'http://schema.org/InStock',
    ),
    );

  list($width, $height) = functions::image_scale_by_width(320, settings::get('product_image_ratio'));
    //TODO: 主图内容
    $main_image = !empty($product->images) ? @array_shift(array_values($product->images)) : 'no_image.png';
    if(u_utils::startWith("http",$main_image)) {
        $main_original = $main_image;
        $main_thumbnail = $main_original;
        $main_thumbnail_2x = $main_original;
    } else {
        $main_original = WS_DIR_IMAGES . $main_image;
        $main_thumbnail = functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $main_image, $width, $height, settings::get('product_image_clipping'), settings::get('product_image_trim'));
        $main_thumbnail_2x = functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $main_image, $width*2, $height*2, settings::get('product_image_clipping'), settings::get('product_image_trim'));
    }
  $_page->snippets = array(
    'product_id' => $product->id,
    'link' => document::ilink('product', array(), true),
    'code' => $product->code,
    'sku' => $product->sku,
    'mpn' => $product->mpn,
    'gtin' => $product->gtin,
    'name' => $product->name,
    'short_description' => !empty($product->short_description) ? $product->short_description : '',
    'description' => !empty($product->description) ? $product->description : '<em style="opacity: 0.65;">'. language::translate('text_no_product_description', 'There is no description for this product yet.') . '</em>',
    'head_title' => !empty($product->head_title) ? $product->head_title : $product->name,
    'meta_description' => !empty($product->meta_description) ? $product->meta_description : $product->short_description,
    'keywords' => $product->keywords,
    'attributes' => !empty($product->attributes) ? preg_split('#\r\n|\r|\n#', $product->attributes) : array(),
    'image' => array(
      'original' => $main_original,
      'thumbnail' => $main_thumbnail_2x,
      'thumbnail_2x' => $main_thumbnail_2x,
      'viewport' => array(
        'width' => $width,
        'height' => $height,
      ),
    ),
    'sticker' => '',
    'extra_images' => array(),
    'manufacturer' => array(),
    'regular_price' => tax::get_price($product->price, $product->tax_class_id),
    'campaign_price' => (isset($product->campaign['price']) && $product->campaign['price'] > 0) ? tax::get_price($product->campaign['price'], $product->tax_class_id) : null,
    'tax_class_id' => $product->tax_class_id,
    'including_tax' => !empty(customer::$data['display_prices_including_tax']) ? true : false,
    'total_tax' => tax::get_tax(!empty($product->campaign['price']) ? $product->campaign['price'] : $product->price, $product->tax_class_id),
    'tax_rates' => array(),
    'quantity' => @round($product->quantity, $product->quantity_unit['decimals']),
    'quantity_unit' => $product->quantity_unit,
    'stock_status' => settings::get('display_stock_count') ? round($product->quantity, $product->quantity_unit['decimals']) .' '. $product->quantity_unit['name'] : language::translate('title_in_stock', 'In Stock'),
    'delivery_status' => !empty($product->delivery_status['name']) ? $product->delivery_status['name'] : '',
    'sold_out_status' => !empty($product->sold_out_status['name']) ? $product->sold_out_status['name'] : '',
    'orderable' => $product->sold_out_status['orderable'],
    'cheapest_shipping_fee' => null,
    'catalog_only_mode' => settings::get('catalog_only_mode'),
    'options' => array(),
  );

    // Extra Images
    //TODO: 底部小图内容
  list($width, $height) = functions::image_scale_by_width(160, settings::get('product_image_ratio'));
  foreach (array_slice(array_values($product->images), 1) as $image) {
      $original = "";
      $thumbnail = "";
      $thumbnail_2x = "";
      if(u_utils::startWith("http",$image)) {
          $original = $image;
          $thumbnail = $image;
          $thumbnail_2x = $image;
      } else {
          $original = WS_DIR_IMAGES . $image;
          $thumbnail = functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $image, $width, $height, settings::get('product_image_clipping'), settings::get('product_image_trim'));
          $thumbnail_2x = functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $image, $width*2, $height*2, settings::get('product_image_clipping'), settings::get('product_image_trim'));
      }
    $_page->snippets['extra_images'][] = array(
      'original' => $original,
      'thumbnail' => $thumbnail,
      'thumbnail_2x' => $thumbnail_2x,
      'viewport' => array(
        'width' => $width,
        'height' => $height,
      ),
    );
  }

// Watermark Images
  if (settings::get('product_image_watermark')) {
    $_page->snippets['image']['original'] = functions::image_process(FS_DIR_HTTP_ROOT . $_page->snippets['image']['original'], array('watermark' => true));
    foreach (array_keys($_page->snippets['extra_images']) as $key) {
      $_page->snippets['extra_images'][$key]['original'] = functions::image_process(FS_DIR_HTTP_ROOT . $_page->snippets['extra_images'][$key]['original'], array('watermark' => true));
    }
  }

// Stickers
  if (!empty($product->campaign['price'])) {
    $percentage = round(($product->price - $product->campaign['price']) / $product->price * 100);
    $_page->snippets['sticker'] = '<div class="sticker sale" title="'. language::translate('title_on_sale', 'On Sale') .'">'. language::translate('sticker_sale', 'Sale') .'<br />-'. $percentage .' %</div>';
  } else if ($product->date_created > date('Y-m-d', strtotime('-'.settings::get('new_products_max_age')))) {
    $_page->snippets['sticker'] = '<div class="sticker new" title="'. language::translate('title_new', 'New') .'">'. language::translate('sticker_new', 'New') .'</div>';
  }

// Manufacturer
  if (!empty($product->manufacturer)) {
    $schema_json['brand']['name'] = $product->manufacturer->name;
    $_page->snippets['manufacturer'] = array(
      'id' => $product->manufacturer->id,
      'name' => $product->manufacturer->name,
      'image' => array(),
      'link' => document::ilink('manufacturer', array('manufacturer_id' => $product->manufacturer->id)),
    );

    if (!empty($product->manufacturer->image)) {
      $_page->snippets['manufacturer']['image'] = array(
        'original' => WS_DIR_IMAGES . $product->manufacturer->image,
        'thumbnail' => functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product->manufacturer->image, 200, 60),
        'thumbnail_2x' => functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product->manufacturer->image, 400, 120),
        'viewport' => array(
          'width' => $width,
          'height' => $height,
        ),
      );
    }
  }

// Tax
  $tax_rates = tax::get_tax_by_rate(!empty($product->campaign['price']) ? $product->campaign['price'] : $product->price, $product->tax_class_id);
  if (!empty($tax_rates)) {
    foreach ($tax_rates as $tax_rate) {
      $_page->snippets['tax_rates'][] = currency::format($tax_rate['tax']) .' ('. $tax_rate['name'] .')';
    }
  }

// Cheapest shipping
  if (settings::get('display_cheapest_shipping')) {
    $shipping = new mod_shipping('local');
    $cheapest_shipping = $shipping->cheapest(
      array(
        $product->id => array(
          'quantity' => 1,
          'product_id' => $product->id,
          'price' => !empty($product->campaign['price']) ? $product->campaign['price'] : $product->price,
          'tax_class_id' => $product->tax_class_id,
          'weight' => $product->weight,
          'weight_class' => $product->weight_class,
          'dim_x' => $product->dim_x,
          'dim_x' => $product->dim_x,
          'dim_y' => $product->dim_y,
          'dim_z' => $product->dim_z,
          'dim_class' => $product->dim_class,
        ),
      ),
      !empty($product->campaign['price']) ? $product->campaign['price'] : $product->price,
      tax::get_tax(!empty($product->campaign['price']) ? $product->campaign['price'] : $product->price, $product->tax_class_id),
      currency::$selected['code'],
      customer::$data
    );
    if (!empty($cheapest_shipping)) {
      list($module_id, $option_id) = explode(':', $cheapest_shipping);
      if (empty($shipping->data['options'][$module_id]['options'][$option_id]['error'])) {
        $shipping_cost = $shipping->data['options'][$module_id]['options'][$option_id]['cost'];
        $shipping_tax_class_id = $shipping->data['options'][$module_id]['options'][$option_id]['tax_class_id'];
        $_page->snippets['cheapest_shipping_fee'] = tax::get_price($shipping_cost, $shipping_tax_class_id);
      }
    }
  }
// Options 商品规格
  if (count($product->options) > 0) {
      $isTowImg = false;

    foreach ($product->options as $group) {
      $values = '';
      switch ($group['function']) {

        case 'checkbox':

          foreach ($group['values'] as $value) {

            $price_adjust_text = '';
            $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
            $tax_adjust = currency::format(tax::get_tax($value['price_adjust'], $product->tax_class_id));

            if ($value['price_adjust']) {
              $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
              if ($value['price_adjust'] > 0) $price_adjust_text = ' +' . $price_adjust_text;
            }

            $values .= '<div class="checkbox">' . PHP_EOL
                     . '  <label>' . functions::form_draw_checkbox('options['.$group['name'].'][]', $value['name'], true, 'data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"' . (!empty($group['required']) ? ' required="required"' : '')) .' '. $value['name'] . $price_adjust_text . '</label>' . PHP_EOL
                     . '</div>';
          }
          break;

        case 'input':

          $value = array_shift($group['values']);

          $price_adjust_text = '';
          $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
          $tax_adjust = currency::format(tax::get_tax($value['price_adjust'], $product->tax_class_id));

          if ($value['price_adjust']) {
            $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
            if ($value['price_adjust'] > 0) $price_adjust_text = ' +'.$price_adjust_text;
          }

          $values .= functions::form_draw_text_field('options['.$group['name'].']', isset($_POST['options'][$group['name']]) ? true : $value['value'], 'data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"' . (!empty($group['required']) ? ' required="required"' : '')) . $price_adjust_text . PHP_EOL;
          break;

        case 'radio':

          foreach ($group['values'] as $value) {

            $price_adjust_text = '';
            $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
            $tax_adjust = currency::format(tax::get_tax($value['price_adjust'], $product->tax_class_id));

            if ($value['price_adjust']) {
              $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
              if ($value['price_adjust'] > 0) $price_adjust_text = ' +'.$price_adjust_text;
            }

            $values .= '<div class="radio">' . PHP_EOL
                     . '  <label>'. functions::form_draw_radio_button('options['.$group['name'].']', $value['name'], true, 'data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"' . (!empty($group['required']) ? ' required="required"' : '')) .' '. $value['name'] . $price_adjust_text . '</label>' . PHP_EOL
                     . '</div>';
          }
          break;

        case 'select': {//TODO: 这里是产品select 绘制的代码

            // 1. 如果是平级结构，交给box_same_option 展示

//            include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/box_product.inc.php');
            // 2. 如果是层级结构，交给box_tree_option 展示
            $options = array();
            $groupName = $group['name'];
//          $options = array(array('-- '. language::translate('title_select', 'Select') .' --', ''));
            foreach ($group['values'] as $value) {
                if($isTowImg === false && !empty($value['links'])) {
                    $isTowImg = true;
                }
                $price_adjust_text = '';
                $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
                $tax_adjust = currency::format(tax::get_tax($value['price_adjust'], $product->tax_class_id));

                if ($value['price_adjust']) {
                    $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
                    if ($value['price_adjust'] > 0) $price_adjust_text = ' +' . $price_adjust_text;
                }

                $options[] = array($value['name'] . $price_adjust_text,
                    $value['name'],$value['links'],$product->id,$group['id'],$value['id'],$groupName,
                    'data-price-adjust="' . (float)$price_adjust . '" data-tax-adjust="' . (float)$tax_adjust . '"');
            }


//          $values .= functions::form_draw_select_field('options['.$group['name'].']', $options, true, false, !empty($group['required']) ? 'required="required"' : '');
            // 前期默认只对款式和颜色做处理，因为这两个有show view pic 需求，后期看修改select标记如select-pic通过这种来区分不同的交互需求
            if (strtolower($groupName) === "style") {// 款式
                // 拿到links，默认就要在页面款式缩略图
                $values .= functions::form_draw_picture_select_field('options[' . $group['name'] . ']',
                    $options, true, false, !empty($group['required']) ? 'required="required"' : '');
            } else if (strtolower($groupName) === "color") {// 颜色
                // 拿到颜色值和links，点击后显示具体的颜色款式图
                $values .= functions::form_draw_link_color_lump('options[' . $group['name'] . ']',
                    $options, true, false, !empty($group['required']) ? 'required="required"' : '');
            } else {
                $values .= functions::form_draw_blocks_select_field('options[' . $group['name'] . ']',
                    $options, true, false, !empty($group['required']) ? 'required="required"' : '');
            }

            break;
        }

        case 'textarea':

          $value = array_shift($group['values']);

          $price_adjust_text = '';
          $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
          $tax_adjust = currency::format(tax::get_tax($value['price_adjust'], $product->tax_class_id));

          if ($value['price_adjust']) {
            $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
            if ($value['price_adjust'] > 0) {
              $price_adjust_text = ' <br />+'. currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
            }
          }

          $values .= functions::form_draw_textarea('options['.$group['name'].']', isset($_POST['options'][$group['name']]) ? true : $value['value'], !empty($group['required']) ? 'required="required"' : '') . $price_adjust_text. PHP_EOL;
          break;
      }

      $_page->snippets['options'][] = array(
        'name' => $group['name'],
        'description' => $group['description'],
        'required' => !empty($group['required']) ? 1 : 0,
        'values' => $values,
      );
    }
      // 这个标记是用来在页面判断是否要显示两个img。 用在style+color这种需要预览的商品上。原框架只有一个img没法实现叠加效果。为了简便
      // 碰到这种情况，使用两个img通过定位来叠加，就需要这个标记。如果option不需要预览，则页面按照原来的方式布局和现实，不会出现另外一个img
    $_page->snippets['isTwoImg'] = $isTowImg;
//      $view = new view();
//      $optionHtml = $view->stitch("views/box_product_info_option");
//      $_page->snippets['option_html'] = $optionHtml;
  }

  document::$snippets['head_tags']['schema_json'] = '<script type="application/ld+json">'. json_encode($schema_json) .'</script>';

  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo $_page->stitch('pages/product.ajax');
  } else {
    echo $_page->stitch('pages/product');
  }
