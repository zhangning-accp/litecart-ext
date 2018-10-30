<?php
    /**
     * 查询后的页面page
     */
?>

<aside id="sidebar">
  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/column_left.inc.php'); ?>
</aside>

<main id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <div id="box-search-results" class="box">

    <?php if ($products) { ?>
    <div class="btn-group pull-right hidden-xs">
<?php
  $separator = false;
  foreach ($sort_alternatives as $key => $value) {
    if ($_GET['sort'] == $key) {
      echo '<span class="btn btn-default active">'. $value .'</span>';
    } else {
      echo '<a class="btn btn-default" href="'. document::href_ilink(null, array('sort' => $key), true) .'">'. $value .'</a>';
    }
  }
?>
    </div>
    <?php } ?>

    <h1 class="title"><?php echo $title; ?></h1>

    <?php if ($products) { ?>
    <div class="products row half-gutter">
      <?php foreach ($products as $product) echo functions::draw_listing_product($product, 'column'); ?>
    </div>
    <?php } else { ?>
    <div><em><?php echo language::translate('text_no_matching_results', 'No matching results'); ?></em></div>
    <?php } ?>

    <?php echo $pagination; ?>
  </div>
</main>