<style>
    #headers .top_header_container{
        position: relative;
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        overflow: hidden;
        text-align: left;
    }
    #headers {
        font-size: 0;
        color: #000;
    }
    #headers .main_logo{
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
        margin-top: 12px;
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
    #headers .header_search {
        position: relative;
        display: block;
        height: 50px;
        margin: 0 15px;
        margin-bottom: 0px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        overflow: hidden;
        margin-bottom: 10px;
        display: none;
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
    #headers #header_nav {
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
    #headers .selected {
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
    .second-ul {
        box-sizing: content-box;
    }
    #ex_nav_2 .merch-links .second-ul>a:hover{
        color: #86080b;
        text-decoration: none;
    }
    /*以下是手机端样式*/
    #app{
        overflow: scroll;
        font: 16px/1.5 Roboto,Arial,sans-serif
    }
    .app-input {
        background: #fff;
        color: #0e1111;
        width: 100%;
        height: 46px;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #0e1111;
        border-radius: 0;
    }
    #app-search{
        position: relative;
        width: calc(100% - 44px);
        display: inline-block;
    }
    .app-head-search-btn{
        position:absolute;
        float:right;
        display:inline-block;
        height:40px;
        width:40px;
        cursor:pointer;
        right: 0;
        bottom: 0;
    }
    #ul-list{
        float: right;
        margin: 12px 5px 0;
    }
    @media (max-width:700px){
        .headers {
            display: none;
        }
        #app {
            display: block;
        }
    }
    @media (min-width:700px){
        .headers {
            display: block;
        }
        #app {
            display: none;
        }
    }
    .app-login {
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 1px;
        background-color: transparent;
        border: 0;
        color: #133d8d;
        margin-bottom: 16px;
        cursor: pointer;
    }
    .c-header-navigation-drawer__track {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        position: absolute;
        -webkit-transition: left .2s ease-in-out;
        transition: left .2s ease-in-out;
        z-index: 10000;
    }
    .c-header-navigation-drawer-panel {
        display: inline-block;
        width: 100vw;
        margin-bottom: 250px;
    }
    .c-header-navigation-drawer-panel__menu-item:not(.link-item):first-child {
        border-top: 1px solid #ddd;
    }
    .c-header-navigation-drawer-panel__menu-item:not(.link-item) {
        border-bottom: 1px solid #ddd;
    }
    .c-header-navigation-drawer{
        position: relative;
    }
    .c-header-navigation-drawer-panel ul{
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .c-header-navigation-drawer-panel__menu-item .Link, .c-header-navigation-drawer-panel__menu-item a {
        display: inline-block;
        width: 100%;
        text-align: left;
        padding: 1rem 1.5rem;
        color: #1e1e1e;
        background-color: transparent;
        border: 0;
        cursor: pointer;
    }
    .c-header-navigation-drawer-panel__menu-item .c-icon {
        margin-top: 2px;
        height: 100%;
        float: right;
        fill: #1e1e1e;
    }
    .col i{
        float: right;
        margin-top: 4px;
    }
    .float-left{
        float: left!important;
        margin-right: 10px;
    }
    #app-categories{
        display: none;
    }
    #app-submit {
        position: absolute;
        clip: rect(0,0,0,0);
    }
</style>
<script>

    $(document).ready(function () {
        //为搜索按钮添加点击事件
        $(".search_btn > a").bind("click",function () {
            if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
                $("#js-search").show();
                $("#ex_nav").hide();
                $("#nav_categories").hide();
                $(".header_nav_item:first-child").removeClass("selected");
            } else {
                $(this).addClass("selected");
                $("#js-search").hide(300);
            }
        })
        //为顶部菜单 SHOP添加点击事件
        $(".header_nav_item:first-child").bind("click",function () {
            if ($(this).hasClass("selected")) {//如果已经被选中
                $(this).removeClass("selected");
                $("#ex_nav").hide();
                $("#nav_categories").hide();//隐藏一级分类菜单
                $("#ex_nav_2 > div").hide(300);// 隐藏二级分类菜单
            } else {
                $(this).addClass("selected");
                $("#js-search").hide();
                $("#ex_nav").show();
                $("#nav_categories").show();
                $(".search_btn > a").addClass("selected");
                $("#nav_categories li:first-child").click();
            }
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
            $("#nav_categories li").removeClass("selected");// 移除所有一级分类的选中状态
            $(this).addClass("selected");//给当前一级分类添加选中状态
            var newOffest = offest + liWidth/2 + "px";
            $("#ex_nav > span").animate({left:newOffest});
            var id = $(this).attr("id");
            $("#ex_nav_2 > div").hide();// 隐藏二级类
            $("#" + id + "_div").show(300);// 显示二级分类
        });

        /*以下是手机端相关js*/
        $("#ul-list").bind("click",function () {
            var fontIcon = $("#ul-list i");
            if (fontIcon.hasClass("fa-list-ul")) {
                fontIcon.removeClass("fa-list-ul").addClass("fa-remove");
                $("#app").css({height:"100vh"});
                $("#app-categories").show();
            } else {
                fontIcon.removeClass("fa-remove").addClass("fa-list-ul");
                $("#app").css({height:"auto"});
                $("#app-categories").hide();
            }
        });
        $(".app-head-search-btn").bind("click",function () {
            $("#app-submit").click();
        })
    });
    var categories = <?php echo json_encode($categories)?>;
    /**
     *
     * @param element 当前触发点击事件的html元素
     * @param number  表示第几层菜单 以1，2，3等数字来区分
     */
    function show_child(element,number) {
        var categoriesId = $(element).attr("id").replace("app_li_","");
        //此时的categoriesId由2部分构成  一级分类id_二级分类id
        // 截取id字符串为数组idArray,因此 idArray[0]为1级菜单，idArray[1]为2级菜单
        var idArray = categoriesId.split("_");
        if (number == 1) {
            //获取2级菜单
            var childCategories = categories[idArray[0]]["subitems"];
        } else if (number == 2) {
            //获取3级菜单数组
            var childCategories = categories[idArray[0]]["subitems"][idArray[1]]["subitems"];
        }

        create_app_categories(childCategories,idArray,number);
    }
    function create_app_categories(childCategories,idArray,number) {
        var categoriesTitle ;
        var categoriesId;
        if (number == 1) {
            //获取1级菜单title
            categoriesTitle = categories[idArray[0]]["title"];
            categoriesId = idArray[0]
        } else if (number == 2) {
            //获取2级菜单title
            categoriesTitle = categories[idArray[0]]["subitems"][idArray[1]]["title"];
            categoriesId = idArray[1];
        }
        var html = '<div class="c-header-navigation-drawer-panel" aria-hidden="false"> <nav aria-label="Category"> <ul>';
        html += '<li class="c-header-navigation-drawer-panel__menu-item" onclick="hide_child(this,'+ number +')">' +
            '<button class="Link col"><i class="fa fa-chevron-left float-left"></i>'+ categoriesTitle +'</button></li>';
        for (var item in childCategories) {
            html += create_child_menu_li(childCategories[item],categoriesId,number);
        }
        html += '</ul></nav></div>';
        $("#app-menu").append(html);
        //计算菜单的宽度及偏移量，此处以100为基数
        var width = (number + 1) * 100 ;
        var left = 100 - width
        $("#app-menu").css({width:width + "vw",left:left + "vw"});
    }
    function create_child_menu_li(item,categoriesId,number) {
        var html = '<li id="app_li_' + categoriesId + "_" + item['id'] + '" class="c-header-navigation-drawer-panel__menu-item" ';
        if (!isEmpty(item['subitems'])) {
            html += 'onclick = "show_child(this,' + (number + 1) + ')"';
            html += '><button class="Link col">' + item["title"];
        } else {
            html += '><a class="Link col" href="'+ item["link"] + '">' + item["title"] + '</a> </li>'
        }

        if (!isEmpty(item['subitems'])) {
            html += '<i class="fa fa-chevron-right"></i>';
            html += '</button></li>';
        }

        return html;
    }
    function hide_child(element,number) {
        $(element).parent().parent().parent().remove();
        //计算菜单的宽度及偏移量，此处以100为基数
        var width = number * 100 ;
        var left = 100 - width
        $("#app-menu").css({width:width + "vw",left:left + "vw"});
    }
    function isEmpty(object) {
        var empty = true;
        for (var key in object) {
            empty = false;
            break;
        }
        return empty;
    }
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
              $output = '<div id="'. $item['id'] .'_div"><div class="merch-links">';
              foreach ($item['subitems'] as $subitem) {// 二级分类
//                  $output .= create_li($subitem);
                  $output .= '<ul class="second-ul">
                    <a id="'.$item['id'].'_li" href="'. htmlspecialchars($item['link']) .'"><h2>'. $subitem['title'] .'</h2></a>';
                  foreach ($subitem['subitems'] as $threeSubItem) {
                      $output .= create_li($threeSubItem);
                  }
                  $output .= '</ul>';
              }
              $output .="</div></div>";
          } else {
              $output = '<div id="'. $item['id'] .'_div"><div class="merch-links">';
              $output .= "<span style='color: #999;font-size: 14px;font-weight: bold'>We're sorry, we could not find children categories for \"".$item['title']."\" Please try again.</span></div></div>";
          }
          return $output;
      }
  }
  if (!function_exists("create_app_li")) {
      function create_app_li($item) {
          $output = '<li id="app_li_'.$item['id'].'" class="c-header-navigation-drawer-panel__menu-item" ' ;
          if (!empty($item['subitems'])) {
             $output .= 'onclick = "show_child(this,1)"';
             $output .= '><button class="Link col">' . $item["title"];
          } else {
             $output .= '><a class="Link col" href="' . $item["link"] . '">' . $item["title"] . '</a></li>';
          }

          if (!empty($item['subitems'])) {
              $output .= '<i class="fa fa-chevron-right"></i></button></li>';
          };
          return $output;
      }
  }
?>

<div id="headers" class="headers">
    <div class="top_header_container">
        <div class="main_logo">
            <a id="header_logo_button" href="#" title="Champs Sports Home" alt="Champs Sports Home"></a>
        </div>
        <div class="float-left search_btn">
            <a href="javascript:void(0);" title="Search" class="js-global-nav-link selected"><span class="champs-sprite"></span></a>
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
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<div id="app">
    <div id="app-search" class="js-pushdown app_search removed">

        <form name="search_form" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8" action="<?php echo document::ilink('search')?>" method="get">
    <span class="input_wrap">
            <input id="reduce_input_text_height" class="app-input" title="Enter Your Search" name="query" size="15" maxlength="40" placeholder="SEARCH" autocomplete="off" type="text">
        </span>
            <div class="app-head-search-btn">
                <a id="header_search_button" href="#" title="Submit Search"></a>
                <i class="fa fa-search fa-2x"></i>

            </div>
            <input type="submit" id="app-submit">
        </form>
    </div>
    <div id="ul-list"><i class="fa fa-list-ul fa-2x"></i></div>
    <div id="app-categories">
        <div>
            <button class="app-login">Sign In / VIP</button>
        </div>
        <div class="c-header-navigation-drawer">
            <div class="c-header-navigation-drawer__track" id="app-menu" style="width: 100vw; left: 0vw;">
                <div class="c-header-navigation-drawer-panel" aria-hidden="false">
                    <nav aria-label="Category">
                        <ul>
                            <?php
                            foreach ($categories as $item) {
                                $html = create_app_li($item);
                                echo $html;
                            }
                            ?>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>


</div>
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
