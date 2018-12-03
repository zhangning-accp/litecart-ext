<?php
  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-type: text/html; charset='. language::$selected['charset']);
    document::$layout = 'ajax';
    header('X-Robots-Tag: noindex');
  }

  if (empty(cart::$items)) {
    echo '<p><em>'. language::translate('description_no_items_in_cart', 'There are no items in your cart.') .'</em></p>' . PHP_EOL
       . '<p><a href="'. document::href_ilink('') .'">&lt;&lt; '. language::translate('title_back', 'Back') .'</a></p>';
    return;
  }

  $box_checkout_cart = new view();

  $box_checkout_cart->snippets = array(
    'items' => array(),
    'subtotal' => cart::$total['value'],
    'subtotal_tax' => cart::$total['tax'],
  );

  foreach (cart::$items as $key => $item) {
      //TODO: 订单页面 当前订单商品列表
      $thumbnail = $item['image'];
//      if(!u_utils::startWith("http",$thumbnail)) {
//          list($width, $height) = functions::image_scale_by_width(60, settings::get('product_image_ratio'));
          $thumbnail = u_utils::image_thumbnail($thumbnail,$item['code'],60);
//          $main_original = WS_DIR_IMAGES."products/".$item['code']."/" . $thumbnail;
//          $thumbnail = FS_DIR_HTTP_ROOT . $main_original;
//          $thumbnail = functions::image_thumbnail($thumbnail, $width, $height, 'FIT_USE_WHITESPACING');
//      }
    $box_checkout_cart->snippets['items'][$key] = array(
      'product_id' => $item['product_id'],
      'link' => document::ilink('product', array('product_id' => $item['product_id'])),
      'thumbnail' =>$thumbnail,
      'name' => $item['name'],
      'sku' => $item['sku'],
      'options' => array(),
      'display_price' => customer::$data['display_prices_including_tax'] ? $item['price'] + $item['tax'] : $item['price'],
      'price' => $item['price'],
      'tax' => $item['tax'],
      'tax_class_id' => $item['tax_class_id'],
      'quantity' => (float)$item['quantity'],
      'quantity_unit' => $item['quantity_unit'],
      'error' => $item['error'],
    );

    if (!empty($item['options'])) {
      foreach ($item['options'] as $k => $v) {
        $box_checkout_cart->snippets['items'][$key]['options'][] = $k .': '. $v;
      }
    }
  }
  echo $box_checkout_cart->stitch('views/box_checkout_cart');
