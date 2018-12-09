<?php
    /**
     * 系统底部信息
     */
?>
<style type="text/css">
    #footer_wrapper {
        display: inline-block;
    }

    #footer_wrapper {
        position: relative;
        display: block;
        width: 100%;
        /*min-width: 600px;*/
        max-width: 1920px;
        font-family: Futura, arial, sans-serif;
        font-size: 12px;
        color: #fff;
        background: #000;
        padding: 20px 0;
        margin: 53px auto 0 auto;
    }

    /*.scroll-down-circle.black {*/
        /*background-size: 50% auto;*/
        /*background: #000 url('{snippet:home_path}images/footer/title-arrows-40x26.png') no-repeat center;*/
    /*}*/

    /*.scroll-down-circle {*/
        /*position: absolute;*/
        /*top: -23px;*/
        /*left: 50%;*/
        /*width: 46px;*/
        /*height: 46px;*/
        /*margin-left: -23px;*/
        /*border-radius: 23px;*/
        /*cursor: pointer;*/
        /*background: url('{snippet:home_path}images/footer/title-arrows-40x26.png') center no-repeat;*/
        /*background-size: 50% auto;*/
        /*background-color: #e0e0e0;*/
    /*}*/

    #footer_social h1 {
        margin: 0 0 50px 0;
        font-size: 24px;
        font-weight: 800;
        text-transform: uppercase;
        color: #FFFFFF;
        text-align: center;
    }

    #footer_social div {
        /*border:1px solid red;*/
        height: auto;
        text-align: center;
    }

    #footer_social ul {
        display: inline-block;
        height: 110px;
        padding: 0;
        margin: 5px;
        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        -o-transform: scale(1);
        transform: scale(1);
        -webkit-transition: all .2s linear;
        -moz-transition: all .2s linear;
        -o-transition: all .2s linear;
        transition: all .2s linear;
    }

    #footer_social ul a {
        height: 100%;
        display: block;
    }
    #footer_social ul li {
        background-size: 400%;
        width: 48px;
        height: 48px;
        margin: 10px;
    }
    .email {
        display: block;
        height: 100%;
        width: 110px;
        float: left;
        background: url('{snippet:template_path}images/footer/cs-master-sprite.png') no-repeat;
        background-position: -47px -43px;
    }

    .facebook {
        display: block;
        height: 100%;
        width: 110px;
        float: left;
        background: url('{snippet:template_path}images/footer/cs-master-sprite.png') no-repeat;
        background-position: -94px -43px;
    }

    .twitter {
        display: block;
        height: 100%;
        width: 110px;
        float: left;
        background: url('{snippet:template_path}images/footer/cs-master-sprite.png') no-repeat;
        background-position: -1px -43px;
    }

    .instagram {
        display: block;
        height: 100%;
        width: 110px;
        float: left;
        background: url('{snippet:template_path}images/footer/cs-master-sprite.png') no-repeat;
        background-position: -1px -90px;
    }

    .youtube {
        display: block;
        height: 100%;
        width: 50px;
        float: left;
        background: url('{snippet:template_path}images/footer/cs-master-sprite.png') no-repeat;
        background-position: -47px -90px;
    }

    .snapchat {
        display: block;
        height: 100%;
        width: 110px;
        float: left;
        background: url('{snippet:template_path}images/footer/cs-master-sprite.png') no-repeat;
        background-position: -94px -90px;
    }

    /* bottom links*/
    #footer_big_links {
        /*border: 1px solid red;*/
        text-align: center;
        height: auto;
        font-weight: bold;
    }
    #footer_big_links ul {
        list-style: none;
        /*border: 1px solid beige;*/
        list-style: none;
        padding: 0;
        margin: 0 auto;
        max-width: 1440px;
        text-align: center;
    }

    #footer_big_links ul li {
        display: inline-block;
        width: 198px;
        padding: 20px;
        vertical-align: top;
        text-align: left;

    }

    #footer_big_links a img {
        display: block;
        margin-bottom: 20px;
        height: 40px;
    }



    #footer_big_links h3 {
        text-transform: uppercase;
        color: #b2906a;
        margin-bottom: 10px;
    }

    #footer_big_links p {
        font-size: 12px;
        margin-bottom: 20px;
    }

    #footer_links {
        clear: both;
        text-align: center;
    }
    #footer_links a::after{
        content: "|";
    }
    #footer_links a:last-child::after{
        display: none;
    }
</style>

<div id="footer_wrapper">

<!--    <div class="scroll-down-circle black"></div>-->
    <div id="footer_social">
        <h1>Stay Connected</h1>
        <div>
            <ul>
                <li class="email"><a href="mailto:store@email.com" data-action="open-email-overlay" target="_blank"
                                     title="Sign Up to Receive E-mails"></a></li>
                <li class="facebook"><a href="https://www.facebook.com" target="_blank"
                                        title="Like Champs Sports on Facebook"></a></li>
                <li class="twitter"><a href="https://twitter.com" target="_blank"
                                       title="Follow Champs Sports on Twitter"></a></li>
                <li class="instagram"><a href="http://instagram.com" target="_blank"
                                         title="Follow Champs Sports on Instagram"></a></li>
                <li class="youtube"><a href="http://www.youtube.com" target="_blank"
                                       title="Subscribe Champs Sports on YouTube"></a></li>
                <li class="snapchat"><a href="https://www.snapchat.com" target="_blank"
                                        title="Add Champs Sports on Snapchat"></a></li>
            </ul>
        </div>
    </div>

    <div id="footer_big_links"></div>
</hr>
    <div id="footer_links"></div>

    <p style="color: #FFFFFF;font-weight: bold;text-align: center">© 2018 Footlocker.com, Inc. All rights reserved.
        Prices subject to change without notice. Products shown may not be available in our stores. (<a
                style="color:#b2906a;" href="javascript:return;">more info</a>).</p>

    <div class="we-know-game">
        <img src="<?php document::ilink(WS_DIR_IMAGES . 'logotype.png') ?>" alt="">
    </div>
</div>

<script id="footer_script_big_links" type="text/html">
    <ul>
        {{each items item index}}
            {{if index < 3}}
                <li>
                <h3>{{item.head}}</h3>
                <p>{{item.contents[0]}}</p>
                <a href="{{item.images[0].link}}" target="_blank" title="{{item.contents[2]}}"><img
                        src="{{item.images[0].pc_image}}" alt="">{{item.contents[1]}}</a>
                </li>
            {{/if}}
        {{/each}}
    </ul>
</script>
<script id="footer_script_links" type="text/html">
        {{each items item index}}
            {{if index > 2}}
                <a href="{{item.images[0].link}}" manual_cm_sp="Footer2-_-Textlinks-_-Orderinghelp" title="{{item.head}}">{{item.head}}</a>
            {{/if}}
        {{/each}}
</script>
<script type="text/javascript">
    var url = window.config.platform.url;
    url = url.replace("en/","");
    url += "pages/ajax/site_footer.json";
    //发送ajax请求
    $.post(url,function(data) {
//        data = JSON.parse(data);
        var  footerBigLinks= template('footer_script_big_links', data);
        $("#footer_big_links").html(footerBigLinks);
        var  footerLinks= template('footer_script_links', data);
        $("#footer_links").html(footerLinks);
    });
</script>



<!--<footer id="footer">-->
<!---->
<!--  <div class="row">-->
<!--    <div class="hidden-xs col-sm-fourths col-md-fifths categories">-->
<!--      <h3 class="title">--><?php //echo language::translate('title_categories', 'Categories'); ?><!--</h3>-->
<!--      <ul class="list-unstyled">-->
<!--        --><?php //foreach ($categories as $category) echo '<li><a href="'. htmlspecialchars($category['link']) .'">'. $category['name'] .'</a></li>' . PHP_EOL; ?>
<!--      </ul>-->
<!--    </div>-->
<!---->
<!--    --><?php //if ($manufacturers) { ?>
<!--    <div class="hidden-xs hidden-sm col-md-fifths manufacturers">-->
<!--      <h3 class="title">--><?php //echo language::translate('title_manufacturers', 'Manufacturers'); ?><!--</h3>-->
<!--      <ul class="list-unstyled">-->
<!--      --><?php //foreach ($manufacturers as $manufacturer) echo '<li><a href="'. htmlspecialchars($manufacturer['link']) .'">'. $manufacturer['name'] .'</a></li>' . PHP_EOL; ?>
<!--      </ul>-->
<!--    </div>-->
<!--    --><?php //} ?>
<!---->
<!--    <div class="col-xs-halfs col-sm-fourths col-md-fifths account">-->
<!--      <h3 class="title">--><?php //echo language::translate('title_account', 'Account'); ?><!--</h3>-->
<!--      <ul class="list-unstyled">-->
<!--        <li><a href="--><?php //echo document::ilink('customer_service'); ?><!--">--><?php //echo language::translate('title_customer_service', 'Customer Service'); ?><!--</a></li>-->
<!--        <li><a href="--><?php //echo document::href_ilink('regional_settings'); ?><!--">--><?php //echo language::translate('title_regional_settings', 'Regional Settings'); ?><!--</a></li>-->
<!--        --><?php //if (empty(customer::$data['id'])) { ?>
<!--        <li><a href="--><?php //echo document::href_ilink('create_account'); ?><!--">--><?php //echo language::translate('title_create_account', 'Create Account'); ?><!--</a></li>-->
<!--        <li><a href="--><?php //echo document::href_ilink('login'); ?><!--">--><?php //echo language::translate('title_login', 'Login'); ?><!--</a></li>-->
<!--        --><?php //} else { ?>
<!--        <li><a href="--><?php //echo document::href_ilink('order_history'); ?><!--">--><?php //echo language::translate('title_order_history', 'Order History'); ?><!--</a></li>-->
<!--        <li><a href="--><?php //echo document::href_ilink('edit_account'); ?><!--">--><?php //echo language::translate('title_edit_account', 'Edit Account'); ?><!--</a></li>-->
<!--        <li><a href="--><?php //echo document::href_ilink('logout'); ?><!--">--><?php //echo language::translate('title_logout', 'Logout'); ?><!--</a></li>-->
<!--        --><?php //} ?>
<!--      </ul>-->
<!--    </div>-->
<!---->
<!--    <div class="col-xs-halfs col-sm-fourths col-md-fifths information">-->
<!--      <h3 class="title">--><?php //echo language::translate('title_information', 'Information'); ?><!--</h3>-->
<!--      <ul class="list-unstyled">-->
<!--        --><?php //foreach ($pages as $page) echo '<li><a href="'. htmlspecialchars($page['link']) .'">'. $page['title'] .'</a></li>' . PHP_EOL; ?>
<!--      </ul>-->
<!--    </div>-->
<!---->
<!--    <div class="hidden-xs col-sm-fourths col-md-fifths contact">-->
<!--      <h3 class="title">--><?php //echo language::translate('title_contact', 'Contact'); ?><!--</h3>-->
<!---->
<!--      <p>--><?php //echo nl2br(settings::get('store_postal_address')); ?><!--</p>-->
<!---->
<!--      --><?php //if (settings::get('store_phone')) { ?>
<!--      <p>--><?php //echo functions::draw_fonticon('fa-phone'); ?><!-- <a href="tel:--><?php //echo settings::get('store_phone'); ?><!--">--><?php //echo settings::get('store_phone'); ?><!--</a><p>-->
<!--      --><?php //} ?>
<!---->
<!--      <p>--><?php //echo functions::draw_fonticon('fa-envelope'); ?><!-- <a href="mailto:--><?php //echo settings::get('store_email'); ?><!--">--><?php //echo settings::get('store_email'); ?><!--</a></p>-->
<!--    </div>-->
<!--  </div>-->
<!--</footer>-->
<!---->
<!--<div id="copyright" class="twelve-eighty">-->
<!--  <!-- LiteCart is provided free under license CC BY-ND 4.0 - https://creativecommons.org/licenses/by-nd/4.0/. Removing the link back to litecart.net without permission is a violation - https://www.litecart.net/addons/172/removal-of-attribution-link -->
<!--  <div class="notice">Copyright &copy; --><?php //echo date('Y'); ?><!-- --><?php //echo settings::get('store_name'); ?><!--. All rights reserved &middot; Powered by <a href="https://www.litecart.net" target="_blank" title="Free e-commerce platform">LiteCart®</a></div>-->
<!--</div>-->