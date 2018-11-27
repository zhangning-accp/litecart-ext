<?php
    /** ------------------------------------------------
     *   前端商品弹出层和详情页的view
     */
?>
<style type="text/css">
    .product_sizes_content {
        position: relative;
        text-align: left;
        border: 1px solid #AFAFAF;
        background: #ededed;
        width:100%;
        padding: 10px;
        margin: 0 0 20px 0;
        box-sizing: border-box;
        overflow: hidden;
    }
    .product_sizes {
        font-size: 14px;
        color: #666666;
        font-weight: bold;
        float:left;
        width: 100%;
        text-align: left;
    }
    .product_sizes a {
        display: inline-block;
        margin: 0 3px 3px 0;
        padding: 0 5px 0 5px;
        min-width: 50px;
        width: auto;
        height: 40px;
        background: #FFFFFF;
        color: #000000;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        border: 1px solid #AFAFAF;
        font-family: Futura,Arial,Sans-Serif;
        line-height:40px;
        border-radius: 4px;
    }
    .product_color {
        border:3px solid #86797d;
    }

    #product_img {
        <?php //  普通商品不需要讲主图进行缩减。
        if($this->snippets['isTwoImg']) {
            echo "max-width: 55%;margin-top: 15%;";
        }?>

    }
    .add_to_cart{
        background: #b2906a;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid #b2906a;
        font-weight:bold;
        color: #ffffff;
        font-family: Futura,Arial,Sans-Serif;
        font-size: 16px;
    }

    .title>h1{
        display: inline-block;
        color:#000000;
        font-size: 24px;
        line-height: 24px;
        text-transform: uppercase;
        font-weight: 800;
        font-family: Futura,Arial,Sans-Serif;
    }
    .nav-tabs > li > a{
        border-radius: 0px 0px 0 0;
        font: 18px/1.0 Roboto,Arial,sans-serif;
    }
    .tab-content-style {
        border-left: 1px solid #e5e5e5;
        border-right: 1px solid #e5e5e5;
        border-bottom: 1px solid #e5e5e5;
        padding: 10px 10px 10px 10px;
        font: 14px/2 Roboto,Arial,sans-serif;
    }
    .font_specification {
        font: 14px/1.5 Roboto,Arial,sans-serif;
    }
    /*Style css*/
    .product_sizes div{
        cursor: pointer;
    }
</style>
<div id="box-product" class="box" style="max-width: 980px;" data-id="<?php echo $product_id; ?>"
     data-name="<?php echo htmlspecialchars($name); ?>"
     data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>">
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="image-wrapper">
                <a href="<?php echo htmlspecialchars($image['original']); ?>" data-toggle="lightbox"
                   data-gallery="product" <?php if($this->snippets['isTwoImg']) echo "style=\"position: absolute;\""?>>
                    <img class="img-responsive" src="<?php echo htmlspecialchars($image['thumbnail']); ?>"
                         srcset="<?php echo htmlspecialchars($image['thumbnail']); ?> 1x, <?php echo htmlspecialchars($image['thumbnail_2x']); ?> 2x"
                         alt="" title="<?php echo htmlspecialchars($name); ?>" id="product_img"/>
                    <?php echo $sticker; ?>
                </a>
                <?php if($this->snippets['isTwoImg']) echo "<img src='' id='color_img'>"?>
            </div>

            <?php if ($extra_images) { ?>
                <div class="extra-images row half-gutter">
                    <?php foreach ($extra_images as $image) { ?>
                        <div class="col-xs-2">
                            <div class="extra-image">
                                <a href="<?php echo htmlspecialchars($image['original']); ?>" data-toggle="lightbox"
                                   data-gallery="product">
                                    <img class="img-responsive"
                                         src="<?php echo htmlspecialchars($image['thumbnail']); ?>"
                                         srcset="<?php echo htmlspecialchars($image['thumbnail']); ?> 1x, <?php echo htmlspecialchars($image['thumbnail_2x']); ?> 2x"
                                         alt="" title="<?php echo htmlspecialchars($name); ?>"/>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="col-sm-6 col-md-6 font_specification">
            <div class="title"><!--product title and descritions-->
            <h1><?php echo $name; ?></h1>
            <?php if ($short_description) { ?>
                <p class="short-description">
                    <?php echo $short_description; ?>
                </p>
            <?php } ?>

            <?php if (!empty($manufacturer)) { ?>
                <div class="manufacturer">
                    <a href="<?php echo htmlspecialchars($manufacturer['link']); ?>">
                        <?php if ($manufacturer['image']) { ?>
                            <img src="<?php echo functions::image_thumbnail($manufacturer['image']['thumbnail'], 0, 48); ?>"
                                 srcset="<?php echo htmlspecialchars($manufacturer['image']['thumbnail']); ?> 1x, <?php echo htmlspecialchars($manufacturer['image']['thumbnail_2x']); ?> 2x"
                                 alt="<?php echo htmlspecialchars($manufacturer['name']); ?>"
                                 title="<?php echo htmlspecialchars($manufacturer['name']); ?>"/>
                        <?php } else { ?>
                            <h3><?php echo $manufacturer['name']; ?></h3>
                        <?php } ?>
                    </a>
                </div>
            <?php } ?>
            </div>
            <div class="price-wrapper">
                <?php if ($campaign_price) { ?>
                    <del class="regular-price"><?php echo currency::format($regular_price); ?></del> <strong
                            class="campaign-price"><?php echo currency::format($campaign_price); ?></strong>
                <?php } else { ?>
                    <span class="price"><?php echo currency::format($regular_price); ?></span>
                <?php } ?>
            </div>

            <div class="tax" style="margin: 0 0 1em 0;">
                <?php if ($tax_rates) { ?>
                    <?php echo $including_tax ? language::translate('title_including_tax', 'Including Tax') : language::translate('title_excluding_tax', 'Excluding Tax'); ?>:
                    <span class="total-tax"><?php echo currency::format($total_tax); ?></span>
                <?php } else { ?>
                    <?php echo language::translate('title_excluding_tax', 'Excluding Tax'); ?>
                <?php } ?>
            </div>

            <?php if ($cheapest_shipping_fee !== null) { ?>
                <div class="cheapest-shipping" style="margin: 1em 0;">
                    <?php echo functions::draw_fonticon('fa-truck'); ?><?php echo strtr(language::translate('text_cheapest_shipping_from_price', 'Cheapest shipping from <strong class="value">%price</strong>'), array('%price' => currency::format($cheapest_shipping_fee))); ?>
                </div>
            <?php } ?>

<!--            --><?php //if ($sku || $mpn || $gtin || $code) { ?>
<!--                <div class="codes" style="margin: 1em 0;">-->
<!--                    --><?php //if ($sku) { ?>
<!--                        <div class="sku">-->
<!--                            --><?php //echo language::translate('title_sku', 'SKU'); ?><!--:-->
<!--                            <span class="value">--><?php //echo $sku; ?><!--</span>-->
<!--                        </div>-->
<!--                        <div class="sku">-->
<!--                            --><?php //echo language::translate('title_code', 'CODE'); ?><!--:-->
<!--                            <span class="value">--><?php //echo $code; ?><!--</span>-->
<!--                        </div>-->
<!--                    --><?php //} ?>
<!---->
<!--                    --><?php //if ($mpn) { ?>
<!--                        <div class="mpn">-->
<!--                            --><?php //echo language::translate('title_mpn', 'MPN'); ?><!--:-->
<!--                            <span class="value">--><?php //echo $mpn; ?><!--</span>-->
<!--                        </div>-->
<!--                    --><?php //} ?>
<!---->
<!--                    --><?php //if ($gtin) { ?>
<!--                        <div class="gtin">-->
<!--                            --><?php //echo language::translate('title_gtin', 'GTIN'); ?><!--:-->
<!--                            <span class="value">--><?php //echo $gtin; ?><!--</span>-->
<!--                        </div>-->
<!--                    --><?php //} ?>
<!--                </div>-->
<!--            --><?php //} ?>

            <div class="stock-status" style="margin: 1em 0;">
                <?php if ($quantity > 0) { ?>
                    <div class="stock-available">
                        <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
                        <span class="value"><?php echo $stock_status; ?></span>
                    </div>
                    <?php if ($delivery_status) { ?>
                        <div class="stock-delivery">
                            <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
                            <span class="value"><?php echo $delivery_status; ?></span>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <?php if ($sold_out_status) { ?>
                        <div class="<?php echo $orderable ? 'stock-partly-available' : 'stock-unavailable'; ?>">
                            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
                            <span class="value"><?php echo $sold_out_status; ?></span>
                        </div>
                    <?php } else { ?>
                        <div class="stock-unavailable">
                            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
                            <span class="value"><?php echo language::translate('title_sold_out', 'Sold Out'); ?></span>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <hr/>

            <div class="buy_now" style="margin: 1em 0;">
                <?php echo functions::form_draw_form_begin('buy_now_form', 'post'); ?>
                <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>

                <?php if ($options) { ?>
                    <?php foreach ($options as $option) { ?>
                        <div class="form-group" id="<?php echo $option['name']; ?>">
                            <label style="text-transform: uppercase;font-weight: bold;float:left"><?php echo $option['name']; ?>:</label>
                            <?php echo functions::form_draw_input('options['.$option['name'].']',"","text","style='border:0px;width:auto;height:20px;float:right;color:#b2906a;font-weight:bold;float:left;' required='required'");?>
                            <div class="product_sizes_content" style="display: block;">
                                <?php echo $option['description'] ? '<div>' . $option['description'] . '</div>' : ''; ?>
                                        <?php echo $option['values']; ?>
                        </div>
                        </div>
                    <?php } // 以下的js函数在public_html/includes/functions/func_form.inc.php
                        // 里的form_draw_blocks_select_field函数里生成的a标签上调用。?>
                <?php } ?>

                <?php if (!$catalog_only_mode) { ?>
                    <div class="form-group">
                        <label><?php echo language::translate('title_quantity', 'Quantity'); ?></label>
                        <div style="display: flex">
                            <div class="input-group">
                                <?php echo (!empty($quantity_unit['decimals'])) ? functions::form_draw_decimal_field('quantity', isset($_POST['quantity']) ? true : 1, $quantity_unit['decimals'], 1, null) : (functions::form_draw_number_field('quantity', isset($_POST['quantity']) ? true : 1, 1)); ?>
                                <?php echo !empty($quantity_unit['name']) ? '<div class="input-group-addon">' . $quantity_unit['name'] . '</div>' : ''; ?>
                            </div>
                            <div style="padding-left: 0.5em; white-space: nowrap;">
                                <?php echo '<button class="btn add_to_cart" name="add_cart_product" value="true" type="submit"' . (($quantity <= 0 && !$orderable) ? ' disabled="disabled"' : '') . '>' . language::translate('title_add_to_cart', 'Add To Cart') . '</button>'; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php echo functions::form_draw_form_end(); ?>
            </div>
        </div>
    </div>

    <?php if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { ?>
        <ul class="nav nav-tabs">
            <?php if ($description) { ?>
                <li><a data-toggle="tab"
                       href="#description"><?php echo language::translate('title_description', 'Description'); ?></a>
                </li><?php } ?>
            <?php if ($attributes) { ?>
                <li><a data-toggle="tab"
                       href="#attributes"><?php echo language::translate('title_attributes', 'Attributes'); ?></a>
                </li><?php } ?>
        </ul>

        <div class="tab-content tab-content-style" style="border-left: 1px solid #e5e5e5;border-right: 1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;padding:10px 10px 10px 10px;">
            <div id="description" class="tab-pane">
                <?php echo $description; ?>
            </div>

            <div id="attributes" class="tab-pane">
                <div class="attributes">
                    <table class="table table-striped table-hover">
                        <?php
                            for ($i = 0; $i < count($attributes); $i++) {
                                if (strpos($attributes[$i], ':') !== false) {
                                    @list($key, $value) = explode(':', $attributes[$i]);
                                    echo '  <tr>' . PHP_EOL
                                        . '    <td>' . trim($key) . ':</td>' . PHP_EOL
                                        . '    <td>' . trim($value) . '</td>' . PHP_EOL
                                        . '  </tr>' . PHP_EOL;
                                } else if (trim($attributes[$i]) != '') {
                                    echo '  <thead>' . PHP_EOL
                                        . '    <tr>' . PHP_EOL
                                        . '      <th colspan="2">' . $attributes[$i] . '</th>' . PHP_EOL
                                        . '    </tr>' . PHP_EOL
                                        . '  </thead>' . PHP_EOL
                                        . '  <tbody>' . PHP_EOL;
                                } else {
                                    echo ' </tbody>' . PHP_EOL
                                        . '</table>' . PHP_EOL
                                        . '<table class="table table-striped table-hover">' . PHP_EOL;
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

</div>

<script>
    Number.prototype.toMoney = function () {
        var number = this;
        var decimals = <?php echo currency::$selected['decimals']; ?>;
        var decimal_point = '<?php echo language::$selected['decimal_point']; ?>';
        var thousands_sep = '<?php echo language::$selected['thousands_sep']; ?>';
        var prefix = '<?php echo currency::$selected['prefix']; ?>';
        var suffix = '<?php echo currency::$selected['suffix']; ?>';
        var sign = (number < 0) ? '-' : '';

        var i = parseInt(number = Math.abs(number).toFixed(decimals)) + '';
        if (number - i == 0) decimals = 0;

        var l = ((l = i.length) > 3) ? l % 3 : 0;
        var f = sign + prefix + (l ? i.substr(0, l) + thousands_sep : '') + i.substr(l).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep) + (decimals ? decimal_point + Math.abs(number - i).toFixed(decimals).slice(decimals) : '') + suffix;

        return f;
    }

    $('#box-product form[name=buy_now_form]').bind('input propertyChange', function (e) {

        var regular_price = <?php echo currency::format_raw($regular_price); ?>;
        var sales_price = <?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>;
        var tax = <?php echo currency::format_raw($total_tax); ?>;

        $(this).find('input[type="radio"]:checked, input[type="checkbox"]:checked').each(function () {
            if ($(this).data('price-adjust')) regular_price += $(this).data('price-adjust');
            if ($(this).data('price-adjust')) sales_price += $(this).data('price-adjust');
            if ($(this).data('tax-adjust')) tax += $(this).data('tax-adjust');
        });

        $(this).find('select option:checked').each(function () {
            if ($(this).data('price-adjust')) regular_price += $(this).data('price-adjust');
            if ($(this).data('price-adjust')) sales_price += $(this).data('price-adjust');
            if ($(this).data('tax-adjust')) tax += $(this).data('tax-adjust');
        });

        $(this).find('input[type!="radio"][type!="checkbox"]').each(function () {
            if ($(this).val() != '') {
                if ($(this).data('price-adjust')) regular_price += $(this).data('price-adjust');
                if ($(this).data('price-adjust')) sales_price += $(this).data('price-adjust');
                if ($(this).data('tax-adjust')) tax += $(this).data('tax-adjust');
            }
        });

        $('#box-product .regular-price').text(regular_price.toMoney());
        $('#box-product .campaign-price').text(sales_price.toMoney());
        $('#box-product .price').text(sales_price.toMoney());
        $('#box-product .total-tax').text(tax.toMoney());
    });

    $('#box-product[data-id="<?php echo $product_id; ?>"] .social-bookmarks .link').off().click(function (e) {
        e.preventDefault();
        prompt("<?php echo language::translate('text_link_to_this_product', 'Link to this product'); ?>", '<?php echo $link; ?>');
    });
    // Click first Style option and first-color.
    $("#Style div span div").first().click();
</script>
