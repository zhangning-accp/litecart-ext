<!--<aside id="sidebar">-->
<!---->
<!--  --><?php
//      // 左边分类菜单部分 首页部分不要左边，但是不是到为何还是有一大块空白区域
////      include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/column_left.inc.php'); ?>
<!--</aside>-->

<main id="content">
  {snippet:notices}

  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_slides.inc.php'); ?>

  <?php
    $home_product = new view();
    echo $home_product->stitch("views/box_home_product");
  ?>


    <!--old content -->
<!--  --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_manufacturer_logotypes.inc.php'); ?>

<!--  <ul class="nav nav-justified nav-tabs">-->
<!--    --><?php //if ($display_campaign_products = (settings::get('box_campaign_products_num_items') && database::num_rows(functions::catalog_products_query(array('campaign' => true, 'limit' => 1)))) ? true : false) { ?><!--<li><a href="#campaign-products" data-toggle="tab">--><?php //echo language::translate('title_campaign_products', 'Campaign Products'); ?><!--</a></li>--><?php //} ?>
<!--    <li><a href="#popular-products" data-toggle="tab">--><?php //echo language::translate('title_popular_products', 'Popular Products'); ?><!--</a></li>-->
<!--    <li><a href="#latest-products" data-toggle="tab">--><?php //echo language::translate('title_latest_products', 'Latest Products'); ?><!--</a></li>-->
<!--  </ul>-->
<!---->
<!--  <div class="tab-content">-->
<!--    --><?php //if ($display_campaign_products) { ?>
<!--    <div class="tab-pane fade in" id="campaign-products">-->
<!--      --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_campaign_products.inc.php'); ?>
<!--    </div>-->
<!--    --><?php //} ?>
<!---->
<!--    <div class="tab-pane fade in" id="popular-products">-->
<!--      --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_popular_products.inc.php'); ?>
<!--    </div>-->
<!---->
<!--    <div class="tab-pane fade in" id="latest-products">-->
<!--      --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_latest_products.inc.php'); ?>
<!--    </div>-->
<!--  </div>-->

</main>
