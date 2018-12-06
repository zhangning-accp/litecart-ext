<!DOCTYPE html>
<html lang="{snippet:language}">
<head> <?php // 对应在public_html/pages/index.inc.php有设置?>
<title>{snippet:title}</title>
<meta charset="{snippet:charset}" />
<meta name="description" content="{snippet:description}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
{snippet:head_tags}
<link rel="stylesheet" href="{snippet:template_path}css/framework.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/app.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/listing_product.css"/>
{snippet:style}<?php // 还未找到在哪里做设置，也可能是任意位置?>
</head>
<body>

<div id="page" class="twelve-eighty">

    <?php // 页面顶部部分 包含logo，国家地区，购物车 ?>
      <header id="header" class="row nowrap center" style="background: #000;margin: 0px">
        <div class="col-xs-auto">
          <a class="logotype" href="<?php echo document::href_ilink(''); ?>">
            <img src="<?php echo WS_DIR_IMAGES; ?>logotype.png" style="max-width: 250px; max-height: 45px;" alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />
          </a>
        </div><!--logo part-->
        <div>
          <?php
                  //TODO: 这里导入box_cart.inc.php 也就是 首页右上角的 Shopping Cart
                  include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_cart.inc.php'); ?>
        </div><!-- Shoping cart part-->
      </header><?php // 顶部部分结束 ?>




    <?php // 页面顶部部分 包含logo，国家地区，购物车 ?>
<!--  <header id="header" class="row nowrap center">-->
<!--<!--      <header id="header" class="banner_content">-->
<!--    <div class="col-xs-auto">-->
<!--      <a class="logotype" href="--><?php //echo document::href_ilink(''); ?><!--">-->
<!--        <img src="--><?php //echo WS_DIR_IMAGES; ?><!--logotype.png" style="max-width: 250px; max-height: 60px;" alt="--><?php //echo settings::get('store_name'); ?><!--" title="--><?php //echo settings::get('store_name'); ?><!--" />-->
<!--      </a>-->
<!--    </div><!--logo part-->
<!---->
<!--    <div class="col-xs-auto text-center hidden-xs">-->
<!--      --><?php //include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_region.inc.php'); ?>
<!--    </div><!--region part-->
<!---->
<!--    <div class="col-xs-auto text-right">-->
<!--      --><?php
//          //TODO: 这里导入box_cart.inc.php 也就是 首页右上角的 Shopping Cart
//          include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_cart.inc.php'); ?>
<!--    </div><!-- Shoping cart part -->
<!--  </header>--><?php //// 顶部部分结束 ?>

  <?php // 顶部下面的站点部分，包含了搜索框和一级大分类(不是左边的大分类)
      include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_site_menu.inc.php');
      // TODO: 这里就是左边分类的部分，新的样式需要调整到其它位置。
//            include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_category_tree.inc.php'); ?>

    <?php // 中间商品部分，包含左边的分类菜单，中间部分的幻灯片和下面部分的商品列表
        // 对应的就是public_html/includes/templates/default.catalog/pages/index.inc.php
    ?>
  <div id="main">
    {snippet:content}
  </div><?php // 中间商品部分结束?>

    <!-- Notice board-->
  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/site_cookie_notice.inc.php'); ?>

  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_BOXES . 'box_site_footer.inc.php'); ?>
</div>

<!-- Back to Top -->
<a id="scroll-up" href="#">
  <?php echo functions::draw_fonticon('fa-chevron-circle-up fa-3x', 'style="color: #000;"'); ?>
</a><!-- Back to Top -->
<!-- Footer -->
{snippet:foot_tags}
<script src="{snippet:template_path}js/app.js"></script>
<script src="{snippet:template_path}js/select.js"></script>
<script src="{snippet:template_path}js/art-template-web.js"></script>
{snippet:javascript}

<style type="text/css">
    #page {
        padding: 0 0px 0 0px;
    }
    #header{
        margin-bottom: 1px;
    }
    .row {
        margin: 0px;
    }
</style>
</body>
</html>