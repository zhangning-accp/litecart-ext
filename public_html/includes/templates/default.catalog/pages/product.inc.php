<aside id="sidebar">
<?php //产品详情页的布局
    // 包括产品本身
    // 只要aside 就能让左边部分留出空白
    // 数据来自public_html/pages/product.inc.php
?>
</aside>

<main id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/box_product.inc.php'); ?>
<!--    --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/box_product_info_option.inc.php'); ?>
  <?php
      // TODO: similar 非常消耗效率。需要看看有没有优化的空间。
      include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_similar_products.inc.php'); ?>
<!---->
<!--  --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_also_purchased_products.inc.php'); ?>
</main>