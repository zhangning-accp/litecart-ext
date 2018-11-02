<?php
    /**
     *  页面左边分类部分。
     */

?>

<div id="column-left">

  <?php
      // TODO: 这里就是左边分类的部分，新的样式需要调整到其它位置。
      //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_category_tree.inc.php'); ?>

  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_filter.inc.php'); // 筛选条件部分?>
<?php //TODO: 左边曾经浏览过的商品?>
  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_recently_viewed_products.inc.php'); ?>

</div>