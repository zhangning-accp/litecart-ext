<?php
    /**
     * 商品详情页底部的推荐商品，但是数据来自public_html/pages/product.inc.php
     */
?>
<div id="box-similar-products" class="box">

  <h2 class="title"><?php echo language::translate('title_similar_products', 'Similar Products'); ?></h2>

  <div class="products row half-gutter text-center">
    <?php foreach ($products as $product) echo functions::draw_listing_product($product); ?>
  </div>

</div>