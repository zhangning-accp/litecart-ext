<?php
    /**
     * //TODO: 前端首页右上角 Shopping Cart....
     */
?>

<div id="cart">
    <a href="<?php echo htmlspecialchars($link); ?>">
        <div class="details">
            <div class="title"><?php echo language::translate('title_shopping_cart', 'Shopping Cart'); ?></div>
            <span class="quantity"><?php echo $num_items; ?></span> <?php echo language::translate('text_items', 'item(s)'); ?> - <span class="formatted_value"><?php echo $cart_total; ?></span>
        </div>
        <img class="image" src="{snippet:template_path}images/<?php echo !empty($num_items) ? 'cart_filled.svg' : 'cart.svg'; ?>" alt="" />
    </a>
</div>
<!--<head>-->
<!--<style type="text/css">-->
<!--    .banner_content {-->
<!--        background: #000000;-->
<!--        display: flex;-->
<!--        top:0px;-->
<!--        right:0px;-->
<!--        height:60px;-->
<!--    }-->
<!---->
<!--    #order_summary{-->
<!--        position: absolute;-->
<!--        top: 0;-->
<!--        right: 0;-->
<!--        height: 60px;-->
<!--        padding: 0 20px;-->
<!--        font-size: 14px;-->
<!--        background: #b2906a;-->
<!--        text-align: center;-->
<!--        text-transform: none;-->
<!---->
<!--    }-->
<!--    .shopping_cart_icon {-->
<!--        width:22px;-->
<!--        height:22px;-->
<!--        background:url('{snippet:template_path}images/shopping-cart-icon.png') center no-repeat;-->
<!--        vertical-align:middle;-->
<!--        display:inline-block;-->
<!--        background-size:contain;-->
<!--    }-->
<!---->
<!--    .cart_count {-->
<!--        display: inline-block;-->
<!--        vertical-align: middle;-->
<!--        width: 32px;-->
<!--        height: 32px;-->
<!--        padding: 0;-->
<!--        margin-left: 5px;-->
<!--        color: #b2906a;-->
<!--        line-height: 32px;-->
<!--        background: #fff;-->
<!--        border-radius: 20px;-->
<!---->
<!--    }-->
<!--    #order_summary a{-->
<!--        display: block;-->
<!--        margin-top: 12px;-->
<!--        color: #fff;-->
<!--        text-decoration: none;-->
<!--    }-->
<!--</style>-->
<!--</head>-->
<!--<div id="cart">-->
<!--<div id="order_summary">-->
<!--  <a href="--><?php //echo htmlspecialchars($link); ?><!--">-->
<!--    <div class="details">-->
<!--      <div class="title">--><?php //echo language::translate('title_shopping_cart', 'Shopping Cart'); ?><!--</div>-->
<!--      <span class="quantity">--><?php //echo $num_items; ?><!--</span> --><?php //echo language::translate('text_items', 'item(s)'); ?><!-- - <span class="formatted_value">--><?php //echo $cart_total; ?><!--</span>-->
<!--    </div>-->
<!--    <img class="image" src="{snippet:template_path}images/--><?php //echo !empty($num_items) ? 'cart_filled.svg' : 'cart.svg'; ?><!--" alt="" />-->
<!--  </a>-->
<!--    <a id="header_cart_button" class="leave_quietly" href="--><?php //echo htmlspecialchars($link);?><!--" rel="nofollow" data-title="Cart" title="View Shopping Cart">-->
<!---->
<!--    <span class="order_summary_value" id="order_summary_content">-->

<!--    <span class="item_count_value">1</span> Item - <span class="subtotal_value">$190.00</span>-->

<!--    </span>-->
<!---->
<!--        <div class="shopping_cart_icon"></div>-->
<!--        <div class="cart_text">SHOPPING CART</div>-->
<!--        <div class="cart_count">--><?php //echo $num_items; ?><!--</div>-->
<!--    </a>-->
<!--</div>-->