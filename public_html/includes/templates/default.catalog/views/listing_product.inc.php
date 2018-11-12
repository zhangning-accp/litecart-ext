<?php
    /** ------------------------------------------------
     *   前端网站首页商品列表的view，该php是某个产品的显示。如果有100个产品，此页面会被执行100次
     */
?>
    <style type="text/css">
        .products.row .product {
            border: 0px;
            border-radius: 0px;
        }

        .products.row .product .price-wrapper .price {
            color: #707070;
            font-size: 16px !important;
            font-family: Futura,arial,sans-serif !important;
        }
        .c-product-search-results .product-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-flex: 0;
            -ms-flex: 0 0 48.5%;
            flex: 0 0 48.5%;
            margin: 0 1.5% 1.5% 0;
        }
        /*@media (max-width: 320px) {*/
            /*.product-container {*/
                /*-webkit-box-flex: 0;*/
                /*-ms-flex: 0 0 100%;*/
                /*margin: 10px 0;*/
            /*}*/

        /*}*/
        /*@media (min-width: 120em) {*/
            /*.c-product-search-results .product-container {*/
                /*-webkit-box-flex: 0;*/
                /*-ms-flex: 0 0 31.333%;*/
                /*flex: 0 0 31.333%;*/
                /*margin: 0 1% 2%;*/
                /*margin-bottom: 16px;*/
            /*}*/
            /*.name {*/
                /*font: 14px/1 Roboto,Arial,sans-serif;*/
                /*padding: 5px 25px;*/
                /*height: 100%;*/
            /*}*/
        /*}*/
        .products.row .product .name {
            display: flex;
            justify-content: center;
            align-content: center;
            flex-direction: column;
            padding: 0 3.75px;
            height: 100%;
        }
        .name {
            font: 16px/1.5 Roboto,Arial,sans-serif;
            padding: 5px 25px;

        }
        .image,.img-responsive{
            padding: 34px 25px 25px;
        }

    </style>
    <script type="text/javascript">
        function productMouseEnter(obj,isEnter) {
            if(isEnter) {
                obj.style = "border:1px solid #A9A9A9;padding:5px 5px";
            } else {
                obj.style = "border:0px; padding:0px 0px";
            }
        }
    </script>
<?php if ($listing_type == 'column') { ?>
    <div class="col-xs-6 col-sm-4 col-md-3">
        <div class="product column" data-id="<?php echo $product_id; ?>"
             data-name="<?php echo htmlspecialchars($name); ?>"
             data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>" onmouseenter="productMouseEnter(this,true)" onmouseleave="productMouseEnter(this,false)">
            <a class="link"<?php echo !empty(document::$settings['product_modal_window']) ? ' data-toggle="lightbox" data-require-window-width="768"' : ''; ?>
               href="<?php echo htmlspecialchars($link) ?>">
                <div class="image-wrapper">
                    <img class="image img-responsive" src="<?php echo htmlspecialchars($image['thumbnail']); ?>"
                         srcset="<?php echo htmlspecialchars($image['thumbnail']); ?> 1x, <?php echo htmlspecialchars($image['thumbnail_2x']); ?> 2x"
                         alt="<?php echo htmlspecialchars($name); ?>"/>
                    <?php echo $sticker; ?>
                </div>

                <div class="name"><?php echo $name; ?></div>

                <div class="price-wrapper">
                    <?php if ($campaign_price) { ?>
                        <s class="regular-price"><?php echo currency::format($regular_price); ?></s> <strong
                                class="campaign-price"><?php echo currency::format($campaign_price); ?></strong>
                    <?php } else { ?>
                        <span class="price"><?php echo currency::format($regular_price); ?></span>
                    <?php } ?>
                </div>
            </a>
        </div>
    </div>
<?php } else if ($listing_type == 'row') { ?>
    <div class="col-xs-12">
        <div class="product hover-light" data-id="<?php echo $product_id; ?>"
             data-name="<?php echo htmlspecialchars($name); ?>"
             data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>">
            <a class="link"<?php echo !empty(document::$settings['product_modal_window']) ? ' data-toggle="lightbox" data-require-window-width="768"' : ''; ?>
               href="<?php echo htmlspecialchars($link) ?>">
                <div class="image-wrapper">
                    <img class="image" src="<?php echo htmlspecialchars($image['thumbnail']); ?>"
                         srcset="<?php echo htmlspecialchars($image['thumbnail']); ?> 1x, <?php echo htmlspecialchars($image['thumbnail_2x']); ?> 2x"
                         alt="<?php echo htmlspecialchars($name); ?>"/>
                    <?php echo $sticker; ?>
                </div>
                <div class="info">
                    <div class="name"><?php echo $name; ?></div>
                    <p class="description"><?php echo $short_description; ?></p>
                </div>
                <div style="font-size: 5em;"></div>
                <div class="price-wrapper">
                    <?php if ($campaign_price) { ?>
                        <s class="regular-price"><?php echo currency::format($regular_price); ?></s> <strong
                                class="campaign-price"><?php echo currency::format($campaign_price); ?></strong>
                    <?php } else { ?>
                        <span class="price"><?php echo currency::format($regular_price); ?></span>
                    <?php } ?>
                </div>
            </a>
        </div>
    </div>
<?php } else {
    trigger_error('Unknown product listing type definition (' . $listing_type . ')', E_USER_WARNING);
} ?>


<?php //if ($listing_type == 'column') { ?>
    <!--  <div class="col-xs-6 col-sm-4 col-md-3">-->
    <!--    <div class="product column hover-light" data-id="--><?php //echo $product_id; ?><!--" data-name="--><?php //echo htmlspecialchars($name); ?><!--" data-price="--><?php //echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?><!--">-->
    <!--      <a class="link"--><?php //echo !empty(document::$settings['product_modal_window']) ? ' data-toggle="lightbox" data-require-window-width="768"' : ''; ?><!-- href="--><?php //echo htmlspecialchars($link) ?><!--">-->
    <!--        <div class="image-wrapper">-->
    <!--          <img class="image img-responsive" src="--><?php //echo htmlspecialchars($image['thumbnail']); ?><!--" srcset="--><?php //echo htmlspecialchars($image['thumbnail']); ?><!-- 1x, --><?php //echo htmlspecialchars($image['thumbnail_2x']); ?><!-- 2x" alt="--><?php //echo htmlspecialchars($name); ?><!--" />-->
    <!--          --><?php //echo $sticker; ?>
    <!--        </div>-->
    <!--        <div class="name">--><?php //echo $name; ?><!--</div>-->
    <!--        <div class="manufacturer">--><?php //echo !empty($manufacturer) ? $manufacturer['name'] : '&nbsp;'; ?><!--</div>-->
    <!--        <div class="price-wrapper">-->
    <!--          --><?php //if ($campaign_price) { ?>
    <!--          <s class="regular-price">--><?php //echo currency::format($regular_price); ?><!--</s> <strong class="campaign-price">--><?php //echo currency::format($campaign_price); ?><!--</strong>-->
    <!--          --><?php //} else { ?>
    <!--          <span class="price">--><?php //echo currency::format($regular_price); ?><!--</span>-->
    <!--          --><?php //} ?>
    <!--        </div>-->
    <!--      </a>-->
    <!--    </div>-->
    <!--  </div>-->
<?php //} else if ($listing_type == 'row') { ?>
    <!--  <div class="col-xs-12">-->
    <!--    <div class="product hover-light" data-id="--><?php //echo $product_id; ?><!--" data-name="--><?php //echo htmlspecialchars($name); ?><!--" data-price="--><?php //echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?><!--">-->
    <!--      <a class="link"--><?php //echo !empty(document::$settings['product_modal_window']) ? ' data-toggle="lightbox" data-require-window-width="768"' : ''; ?><!-- href="--><?php //echo htmlspecialchars($link) ?><!--">-->
    <!--        <div class="image-wrapper">-->
    <!--          <img class="image" src="--><?php //echo htmlspecialchars($image['thumbnail']); ?><!--" srcset="--><?php //echo htmlspecialchars($image['thumbnail']); ?><!-- 1x, --><?php //echo htmlspecialchars($image['thumbnail_2x']); ?><!-- 2x" alt="--><?php //echo htmlspecialchars($name); ?><!--" />-->
    <!--          --><?php //echo $sticker; ?>
    <!--        </div>-->
    <!--        <div class="info">-->
    <!--          <div class="name">--><?php //echo $name; ?><!--</div>-->
    <!--          <p class="description">--><?php //echo $short_description; ?><!--</p>-->
    <!--          <div class="manufacturer">--><?php //echo !empty($manufacturer) ? $manufacturer['name'] : '&nbsp;'; ?><!--</div>-->
    <!--        </div>-->
    <!--        <div class="price-wrapper">-->
    <!--          --><?php //if ($campaign_price) { ?>
    <!--          <s class="regular-price">--><?php //echo currency::format($regular_price); ?><!--</s> <strong class="campaign-price">--><?php //echo currency::format($campaign_price); ?><!--</strong>-->
    <!--          --><?php //} else { ?>
    <!--          <span class="price">--><?php //echo currency::format($regular_price); ?><!--</span>-->
    <!--          --><?php //} ?>
    <!--        </div>-->
    <!--      </a>-->
    <!--    </div>-->
    <!--  </div>-->
<?php //} else trigger_error('Unknown product listing type definition ('. $listing_type .')', E_USER_WARNING); ?>