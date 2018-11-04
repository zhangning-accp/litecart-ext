<?php
    /**
     * 查询后的页面page
     */
?>
<style type="text/css">
    .sort_active {
        font-size: 14px !important;
        text-decoration: none !important;
        background: #b2906a;
        color: #fff !important;
        border: 1px solid #9c7d60;
        font-weight: bold;
        padding: 10px 12px;
        text-transform:Uppercase;
        height:40px;
    }
    .sort_default {
        font-size: 14px !important;
        text-decoration: none !important;
        background: #e0e0e0;
        border: 1px solid #e0e0e0;
        font-weight: bold;
        padding: 10px 12px;
        margin-left: 1px;
        cursor: pointer;
        text-transform:Uppercase;
        height:40px;
    }
    .sort_main_div{
        padding-right: 50px;
        color:#000000;
    }
    a:link{color: #000000;}
    a:visited{color: #000000;}

</style>
<aside id="sidebar">
  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/column_left.inc.php'); ?>
</aside>

<main id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <div id="box-search-results" class="box">

    <?php if ($products) { ?>
    <div class="btn-group pull-right hidden-xs sort_main_div">
<?php
  $separator = false;
  foreach ($sort_alternatives as $key => $value) {
    if ($_GET['sort'] == $key) {
      echo '<span class="sort_active">'. $value .'</span>';
    } else {
      echo '<a class="sort_default" href="'. document::href_ilink(null, array('sort' => $key), true) .'">'. $value .'</a>';
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