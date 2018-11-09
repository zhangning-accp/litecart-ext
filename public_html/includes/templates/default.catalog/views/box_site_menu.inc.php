<style>
    #header .top_header_container{
        position: relative;
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        overflow: hidden;
        text-align: left;
    }
    #header {
        font-size: 0;
        color: #000;
    }
    #header .main_logo{
        display: inline-block;
        width: 15%;
        padding: 10px;
        margin-right: 15px;
        text-align: center;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        vertical-align: middle;
        -webkit-transition: .4s;
        -moz-transition: .4s;
        -o-transition: .4s;
        transition: .4s;
    }
    #header .main_logo a {
        display: inline-block;
        background: url('{snippet:home_path}/images/logotype.png') center center no-repeat #fff;
        background-size: auto auto;
        background-size: contain;
        height: 53px;
        width: 100%;
    }
    .menu_btn,.search_btn {
        display: inline-block;
        vertical-align: middle;
        text-align: center;
        -webkit-transition: .4s;
        -moz-transition: .4s;
        -o-transition: .4s;
        transition: .4s;
    }
    .search_btn a {
        position: relative;
        display: block;
        margin: 0 5px;
        height: 52px;
        width: 52px;
        text-align: center;
        background: url('{snippet:home_path}/images/categories/large-search-magnifying.png') no-repeat center #000;
        background-size: auto auto;
        background-size: 50%;
        border-radius: 3px;
    }
    .search_btn a.selected::after {
        bottom: -44px;
    }
    .search_btn a::after {
        display: block;
        content: "";
        width: 100%;
        height: 24px;
        background: #000;
        position: absolute;
        bottom: -21px;
        left: 0;
        -webkit-transition: .4s;
        -moz-transition: .4s;
        -o-transition: .4s;
        transition: .4s;
    }
    #header .header_search {
        position: relative;
        display: block;
        height: 50px;
        margin: 0 15px;
        margin-bottom: 0px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .header_search .input_wrap {
        position: absolute;
        left: 0;
        right: 0;
    }
    #locationInput, .header_search #reduce_input_text_height {
        display: inline-block;
        width: 100%;
        padding: 0 20px;
        height: 50px;
        line-height: normal;
        margin: 0;
        border-left: 1px solid #000;
        border-right: none;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        background-color: #000;
        color: #fff;
        font-family: Futura,arial,sans-serif;
        font-size: 24px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 3px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-transition: .4s;
        -moz-transition: .4s;
        -o-transition: .4s;
        transition: .4s;
    }
    #header #header_nav {
        display: inline-block !important;
        vertical-align: middle;
        position: relative;
        display: inline-block;
        width: 75%;
        vertical-align: top;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        font-size: 12px;
        font-weight: 700;
    }
    #header_nav {
        text-align: left;
        margin-top: 0 !important;
    }
    .header_nav_item:first-child{
        margin-left: 10px;
    }
    .header_nav_item {
        position: relative;
        display: block;
        float: left;
        width: auto;
        padding: 34px 30px 28px 5px;
        font-size: 14px;
        text-transform: uppercase;
        box-sizing: border-box;
        background: #fff;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        cursor: pointer;
        -webkit-transition: all .4s;
        transition: all .4s;
        z-index: 1;
    }
    .screenreader {
        position: absolute;
        left: -10000px;
        top: auto;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
    .header_nav_item__dropdown[data-nav="categories"]::before {
        content: "Shop";
    }
    .screenreader {
        position: absolute;
        left: -10000px;
        top: auto;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
    a{
        text-decoration: underline;
        color: #000;
    }
    .header_nav_item__dropdown::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 10px;
        display: block;
        width: 10px;
        height: 6px;
        margin-top: -1px;
        background: url('{snippet:home_path}/images/categories/nav_arrow_down.png') no-repeat center;
    }
    .header_nav_item > a{
        color: #000;
        text-decoration: none;
    }
    .header_nav_item > a::after{
        color: #b2906a;
        text-decoration: none;
        content: "|";
        padding: 0 10px;
    }
    .header_login::before {
        content: "Log In/Register";
    }
    .header_login a::after {
        content: "";
        position: absolute;
        top: 50%;
        right: -10px;
        display: block;
        width: 25px;
        height: 22px;
        margin-top: -9px;
        background: url('{snippet:home_path}/images/login.png') center no-repeat;
        background-size: contain;
    }
    #header .selected {
        color: #b2906a;
    }
    .header_nav_item__dropdown.selected::after {
        background-image: url('{snippet:home_path}/images/categories/nav_arrow_up.png');
    }
    .header_nav_item.selected {
        background: #f0f0f0;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #f0f0f0;
        color: #b2906a;
        z-index: 3;
    }
    #ex_nav {
        display: none;
        position: relative;
        top: -1px;
        left: 0;
        width: 100%;
        border-top: 1px solid #ccc;
        z-index: 2;
    }
    #ex_nav > ul {
        display: none;
        list-style: none;
        width: 100%;
        text-align: center;
        overflow: hidden;
        background: #f0f0f0;
        border-bottom: 1px solid #ccc;
    }
    #ex_nav #nav_categories > div {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        text-align: left;
    }
    #ex_nav > ul li {
        position: relative;
        display: inline-block;
        float: none;
        color: #666;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 30px 0;
        margin: 0 20px;
        width: auto;
        background-image: none;
        cursor: pointer;
        -webkit-transition: .4s;
        -moz-transition: .4s;
        -o-transition: .4s;
        transition: .4s;
    }
    #ex_nav .ex_nav_arrow {
        display: inline;
        /*border-bottom: 1px solid #ccc;*/
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 30px;
        height: 15px;
        padding: 0;
        margin-left: -15px;
        background: url('{snippet:home_path}/images/categories/ex_nav_arrow.jpg') center no-repeat;
        -webkit-transition: .4s;
        -moz-transition: .4s;
        -o-transition: .4s;
        transition: .4s;
    }

    #ex_nav_2 {
        display:block;
        width:100%;
        margin:0 auto;
        font-size: 14px;
    }
    #ex_nav_2>div {
        display:none;
        overflow:hidden;
        height:auto;
        padding:10px 0;
        margin:0 auto;
        border-bottom:1px solid #ccc
    }
    #ex_nav_2 ul.products {
        list-style:none;
        overflow:hidden;
        padding:10px 0 20px 0;
        margin-bottom:20px;
        border-bottom:1px solid #ccc;
        text-align: center;
    }
    #ex_nav_2 ul.products li {
        display:inline-block;
        margin:5px 10px;
        width:100px;
        vertical-align:top
    }
    #ex_nav_2 ul.products li a {
        display:block;
        color:#000;
        text-decoration:none
    }
    #ex_nav_2 ul.products li a span {
        display:block;
        margin:5px 0 0 0
    }
    #ex_nav_2 .merch-links {
        width:100%;
        max-width:960px;
        margin:0 auto;
        text-align:left
    }
    #ex_nav_2 .merch-links ul {
        display:inline-block;
        vertical-align:top;
        width:20%;
        margin:0 -10px 20px 30px;
        color:#666;
        list-style-position:inside
    }
    #ex_nav_2 .merch-links ul h2 {
        text-transform:uppercase;
        font-size:14px;
        margin-bottom:5px
    }
    #ex_nav_2 .merch-links ul li a {
        display:inline-block;
        padding:7px 0;
        color:#666;
        text-decoration:none
    }
    #ex_nav_2 .merch-links ul li a:hover {
        color:#b2906a
    }
    #ex_nav_2 .size-drop {
        margin-bottom:10px;
        cursor:pointer
    }
    #ex_nav_2 .size-drop span {
        display:inline-block;
        padding:5px 0
    }
    #ex_nav_2 > div{
        display: none;
    }
    h2 {
        font-weight: bold;
        color: #666;
    }
    .head-search-btn {
        position:relative;
        float:right;
        display:inline-block;
        background:url('{snippet:home_path}/images/categories/large-search-magnifying.png') no-repeat center #000;
        background-size:50%;
        height:50px;
        width:50px;
        border-radius:0 3px 3px 0;
        cursor:pointer
    }
</style>
<script>
    $(document).ready(function () {
        //为搜索按钮添加点击事件
        $(".search_btn > a").bind("click",function () {
            $(this).toggleClass("selected");
            $("#js-search").toggle();
            $("#ex_nav").toggle();
            $("#nav_categories").toggle();
        })
        //为顶部菜单 SHOP添加点击事件
        $(".header_nav_item:first-child").bind("click",function () {
            $(this).toggleClass("selected");
            $(".search_btn > a").toggleClass("selected");
            $("#js-search").toggle();
            $("#ex_nav").toggle();
            $("#nav_categories").toggle();
            if ($(".header_nav_item:first-child").hasClass("selected")) {
                var $firstLi = $("#nav_categories li:first-child");
                var offest = $firstLi.offset().left;
                var liWidth = $firstLi.css("width").replace("px","");
                var newOffest = offest + liWidth/2 + "px";
                $("#ex_nav > span").css({left:newOffest});
            }
        });
        //为一级分类添加点击事件
        $("#nav_categories li").bind("click",function () {
            var liOffest = $(this).offset().left;
            var divOffest = $("#ex_nav").offset().left;
            var offest = liOffest - divOffest;
            var liWidth = $(this).css("width").replace("px","");
            $("#nav_categories li").removeClass("selected");
            $(this).addClass("selected");
            var newOffest = offest + liWidth/2 + "px";
            $("#ex_nav > span").animate({left:newOffest});
            var id = $(this).attr("id");
            $("#ex_nav_2 > div").hide();
            $("#" + id + "_div").show();
        })
    });

</script>
<?php
  if (!function_exists('custom_draw_site_menu_item')) {
    function custom_draw_site_menu_item($item, $indent=0) {
      if (!empty($item['subitems'])) {// 如果一级分类有子分类，则构建菜单样式。一级分类的遍历在本页52行
        $output = '<li class="dropdown" data-type="'. $item['type'] .'" data-id="'. $item['id'] .'">'
                . '  <a href="'. htmlspecialchars($item['link']) .'" class="dropdown-toggle" data-toggle="dropdown">'. $item['title'] .' <b class="caret"></b></a>'
                . '  <ul class="dropdown-menu">' . PHP_EOL;

        foreach ($item['subitems'] as $subitem) {//遍历二级分类
          $output .= custom_draw_site_menu_item($subitem, $indent+1);
          foreach($subitem['subitems'] as $threeSubItem) {// 遍历三级分类
              $output .= custom_draw_site_menu_item($threeSubItem, $indent+2);
          }
        }

        $output .= '  </ul>' . PHP_EOL
                 . '</li>' . PHP_EOL;

      } else {
        $output = '<li data-type="'. $item['type'] .'" data-id="'. $item['id'] .'">'
                . '  <a href="'. htmlspecialchars($item['link']) .'">'. $item['title'] .'</a>'
                . '</li>' . PHP_EOL;
      }

      return $output;
    }
  }
  if (!function_exists("create_fisrt_menu")) {
      /**
       * 创建一级菜单
       * @param $item
       * @return string
       */
      function create_fisrt_menu($item) {
          $output = '<li id="'.$item['id'].'"><a class="screenreader open_list" ></a>'. $item['title'] .'</li> ';
          return $output;
      }
  }
  if (!function_exists("create_li")) {
      /**
       * 用于创建<li><a></a></li>这样的数据层
       * @param $item
       * @return string
       */
      function create_li($item) {
          $output = '<li><a id="'.$item['id'].'_li" href="'. htmlspecialchars($item['link']) .'">'.$item['title'].'</a></li>';
          return $output;
      }
  }
  if (!function_exists("create_second_menu")) {
      /**
       * 创建二级菜单
       * @param $item
       * @return string
       */
      function create_second_menu($item) {
          $output = '';
          if (!empty($item['subitems'])) {
              $output = '<div id="'. $item['id'] .'_div"><ul class="products">';
              foreach ($item['subitems'] as $subitem) {// 二级分类
                  $output .= create_li($subitem);
                  $output .= '</ul><div class="merch-links"><ul><h2>'. $subitem['title'] .'</h2>';
                  foreach ($subitem['subitems'] as $threeSubItem) {
                      $output .= create_li($threeSubItem);
                  }
                  $output .= '</ul></div></div>';
              }
          }
          return $output;
      }
  }
?>

<div id="header">
    <div class="top_header_container">
        <div class="main_logo">
            <a id="header_logo_button" href="#" title="Champs Sports Home" alt="Champs Sports Home"></a>
        </div>
        <div class="float-left search_btn">
            <a href="javascript:void(0);" title="Search" class="js-global-nav-link"><span class="champs-sprite"></span></a>
        </div>
        <div class="float-left menu_btn">
            <a href="javascript:void(0);" title="menu" class="js-global-nav-link"><span></span></a>
        </div>
        <div id="header_nav">
            <div class="header_nav_item header_nav_item__dropdown" data-nav="categories" title="View Product Categories">
                <a class="screenreader open_menu" href="javascript:void(0);" title="Open View Product Categories Menu">Open View Product Categories Menu</a>
            </div>
            <div class="header_nav_item header_nav_item__link" style="padding-right: 0px">
                <a href="#footer_big_links" manual_cm_sp="TopNav2-_-Stores-_-findastore" title="Find a Champs Store">HELP</a>
            </div>
            <div id="header_login" class="header_nav_item header_login guest" title="Log In or Register">
                <a href="#" rel="nofollow" title="Log In or Register"></a>
            </div>
        </div>
        <!-- Search -->
        <div id="js-search" class="js-pushdown header_search removed">

            <form name="search_form" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8" action="<?php echo document::ilink('search')?>" method="get">
    <span class="input_wrap">
            <input id="reduce_input_text_height" title="Enter Your Search" name="query" size="15" maxlength="40" placeholder="SEARCH" autocomplete="off" type="text">
        </span>
                <div class="head-search-btn">
                    <a id="header_search_button" href="#" title="Submit Search"></a>
                </div>
            </form>
        </div><!--Search end-->
    </div>

    <div id="ex_nav">
        <span class="ex_nav_arrow" style="left: 200px;display: inline"></span>
        <ul id="nav_categories">
            <div>
                <?php
                        foreach ($categories as $item) {// 一级分类
                            $html = create_fisrt_menu($item);
                            echo $html;
                        }
                ?>
            </div>
        </ul>
<!--        <ul id="nav_login">-->
<!--            <li class="guest"></li>-->
<!--            <li class="logged-in" style="display: none;">-->
<!--                <div class="left-column">-->
<!--                    <h2></h2>-->
<!--                    <span></span>-->
<!--                    <a href="javascript:logout('Global Header', 'Log Out', 'true')" class="button" title="Sign Out" rel="nofollow">Sign Out</a>-->
<!--                </div>-->
<!--                <div class="right-column">-->
<!--                    <ul>-->
<!--                        <li><a href="https://www.champssports.com/account/default.cfm?action=accountSummary" title="View Account/VIP Summary">Account/VIP Summary</a></li>-->
<!--                        <li><a href="https://www.champssports.com/account/default.cfm?action=addressBook" title="View Address Book">Address Book</a></li>-->
<!--                        <li><a href="https://www.champssports.com/account/default.cfm?action=editLogin" title="Edit Login">Edit Login</a></li>-->
<!--                        <li><a href="https://www.champssports.com/account/default.cfm?action=orderHistory" title="View Order History">Order History</a></li>-->
<!--                        <li><a href="https://www.champssports.com/wishlist/" title="View My Wishlist">My Wishlist</a></li>-->
<!--                        <li><a href="https://www.champssports.com/account/default.cfm?action=MySCCmain" title="View My Credit Cards">My Credit Cards</a></li>-->
<!--                        <li><a onclick="return myFavoriteStores()" href="#" >My Favorite Stores</a></li>-->
<!--                    </ul>-->
<!--                    <ul>-->
<!--                        <li><a href="https://www.champssports.com/account/default/action--orderStatus/" title="View My Order Status">My Order Status</a></li>-->
<!--                    </ul>-->
    </div>

    <div id="ex_nav_2">
        <?php
            foreach ($categories as $item) {
                $html = create_second_menu($item);
                echo $html;
            }
        ?>
    </div>
</div>
<!--<div id="site-menu">-->
<!--  <nav class="navbar">-->
<!---->
<!--    <div class="navbar-header">-->
<!---->
<!---->
<!--      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#default-menu">-->
<!--        <span class="icon-bar"></span>-->
<!--        <span class="icon-bar"></span>-->
<!--        <span class="icon-bar"></span>-->
<!--      </button>-->
<!--    </div>-->
<!---->
<!--    <div id="default-menu" class="navbar-collapse collapse">-->
<!---->
<!--      <ul class="nav navbar-nav">-->
<!--        <li class="hidden-xs">-->
<!--          <a href="--><?php //echo document::ilink(''); ?><!--" class="navbar-brand">--><?php //echo functions::draw_fonticon('fa-home'); ?><!--</a>-->
<!--        </li>-->
<!---->
<!--        --><?php
//            foreach ($categories as $item) {// 一级分类
//                $html = custom_draw_site_menu_item($item);
//                echo $html;
//            } ?>
<!---->
<!--        --><?php //if ($manufacturers) { ?>
<!--        <li class="manufacturers dropdown">-->
<!--          <a href="#" data-toggle="dropdown" class="dropdown-toggle">--><?php //echo language::translate('title_manufacturers', 'Manufacturers'); ?><!-- <b class="caret"></b></a>-->
<!--          <ul class="dropdown-menu">-->
<!--            --><?php //foreach ($manufacturers as $item) echo custom_draw_site_menu_item($item); ?>
<!--          </ul>-->
<!--        </li>-->
<!--        --><?php //} ?>
<!---->
<!--        --><?php //if ($pages) { ?>
<!--        <li class="information dropdown">-->
<!--          <a href="#" data-toggle="dropdown" class="dropdown-toggle">--><?php //echo language::translate('title_information', 'Information'); ?><!-- <b class="caret"></b></a>-->
<!--          <ul class="dropdown-menu">-->
<!--            --><?php //foreach ($pages as $item) echo custom_draw_site_menu_item($item); ?>
<!--          </ul>-->
<!--        </li>-->
<!--        --><?php //} ?>
<!--      </ul>-->
<!---->
<!--      <ul class="nav navbar-nav navbar-right">-->
<!--        <li class="account dropdown">-->
<!--          <a href="#" data-toggle="dropdown" class="dropdown-toggle">--><?php //echo functions::draw_fonticon('fa-user'); ?><!-- --><?php //echo !empty(customer::$data['id']) ? customer::$data['firstname'] : language::translate('title_sign_in', 'Sign In'); ?><!-- <b class="caret"></b></a>-->
<!--          <ul class="dropdown-menu">-->
<!--            --><?php //if (!empty(customer::$data['id'])) { ?>
<!--              <li><a href="--><?php //echo document::href_ilink('order_history'); ?><!--">--><?php //echo language::translate('title_order_history', 'Order History'); ?><!--</a></li>-->
<!--              <li><a href="--><?php //echo document::href_ilink('edit_account'); ?><!--">--><?php //echo language::translate('title_edit_account', 'Edit Account'); ?><!--</a></li>-->
<!--              <li><a href="--><?php //echo document::href_ilink('logout'); ?><!--">--><?php //echo language::translate('title_logout', 'Logout'); ?><!--</a></li>-->
<!--            --><?php //} else { ?>
<!--              <li>-->
<!--                --><?php //echo functions::form_draw_form_begin('login_form', 'post', document::ilink('login'), false, 'class="navbar-form" style="min-width: 300px;"'); ?>
<!--                  --><?php //echo functions::form_draw_hidden_field('redirect_url', !empty($_GET['redirect_url']) ? $_GET['redirect_url'] : document::link()); ?>
<!---->
<!--                  <div class="form-group">-->
<!--                    --><?php //echo functions::form_draw_email_field('email', true, 'required="required" placeholder="'. language::translate('title_email_address', 'Email Address') .'"'); ?>
<!--                  </div>-->
<!---->
<!--                  <div class="form-group">-->
<!--                    --><?php //echo functions::form_draw_password_field('password', '', 'placeholder="'. language::translate('title_password', 'Password') .'"'); ?>
<!--                  </div>-->
<!---->
<!--                      <div class="btn-group btn-block">-->
<!--                    --><?php //echo functions::form_draw_button('login', language::translate('title_sign_in', 'Sign In')); ?>
<!--                  </div>-->
<!--                --><?php //echo functions::form_draw_form_end(); ?>
<!--              </li>-->
<!--              <li class="text-center">-->
<!--                <a href="--><?php //echo document::href_ilink('create_account'); ?><!--">--><?php //echo language::translate('text_new_customers_click_here', 'New customers click here'); ?><!--</a>-->
<!--              </li>-->
<!---->
<!--              <li class="text-center">-->
<!--                <a href="--><?php //echo document::href_ilink('reset_password'); ?><!--">--><?php //echo language::translate('text_lost_your_password', 'Lost your password?'); ?><!--</a>-->
<!--              </li>-->
<!--            --><?php //} ?>
<!--          </ul>-->
<!--        </li>-->
<!--      </ul>-->
<!--    </div>-->
<!--  </nav>-->
<!--</div>-->
