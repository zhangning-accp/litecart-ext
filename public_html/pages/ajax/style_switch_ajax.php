<?php
    require_once('../../includes/app_header.inc.php');//引入页头
/**
     * 该类用于商品页面点击style时做color，和其他option时使用
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/11/23
     * Time: 16:41
     */

    // 必须的参数：current_product_id,current_group_id,current_value_id, parent_group_id,parent_value_id
    $product_id = $_POST['pid'];
    $group_id = $_POST['gid'];
    $value_id = $_POST['vid'];

    // 根据这几个id找到对应的color和其它option
//    $sql = "SELECT group_id,value_id,links FROM ".DB_TABLE_PRODUCT_OPTION_TREES." where product_id = %d AND parent_group_id = %d AND parent_value_id = %d";
    $sql = "SELECT pot.group_id,pot.value_id,pot.links,ovi.name AS value_name,ogi.name AS group_name from ".DB_TABLE_PRODUCT_OPTION_TREES." AS pot,`lc_option_values_info` AS ovi,`lc_option_groups_info` AS ogi 
	WHERE ogi.group_id = pot.group_id AND ovi.value_id = pot.value_id AND product_id = %d AND parent_group_id = %d AND parent_value_id = %d";
    $parameter_values = array($product_id,$group_id,$value_id);
    $sql = u_utils::builderSQL($sql,$parameter_values);
    $result = database::query($sql);
    $result = database::fetch_full($result);
    $options = array();
    foreach ($result as $item) {
        $group_name = $item['group_name'];
        $value_name = $item['value_name'];
        $link = $item['links'];
        if(!empty($link) && !u_utils::startWith("http")) {
            $link = WS_DIR_IMAGES.$link;
        }
        $options[$group_name][$value_name] = $link;
    }
    // 必须的值：group_name,group_value,links.
//    $result = array(
//        'Color'=>array(
//            '#FFF'=>'products/48da8abf437a492eb24d4b3d2121ea49/Classic/WhiteTshirt.png',
//            '#efce1f'=>'products/48da8abf437a492eb24d4b3d2121ea49/Classic/DaisyTshirt.png',
//            '#d5ab47'=>'products/48da8abf437a492eb24d4b3d2121ea49/Classic/GoldTshirt.png',
//            '#50af72'=>'products/48da8abf437a492eb24d4b3d2121ea49/Classic/IrishGreenTshirt.png'
//        ),
//        'Size'=>array(
//            'X SM (Youth)'=>'',
//            'SM (Youth)'=>"",
//            'MED (Youth)'=>'',
//            'SM'=>'',
//            'MED'=>'',
//            'LG'=>'',
//            'XL'=>'',
//            '2XL'=>'',
//            '3XL'=>'',
//            '4XL'=>'',
//            '5XL'=>''
////            '6XL'=>''
//        )
//    );
//    $html = array();
//    foreach ($options as $key=>$value) {
//        if (strtolower($key) === "color") {
//            $html[$key] = builderColor($key, $value);
//        } else {
//            $html[$key] = builderOther($key, $value);
//        }
//    }
//
//    function builderColor($name,&$items) {
//        $inner_html = "<label style='text-transform: uppercase;font-weight: bold;float:left'>".$name."</label>";
//        $inner_html.="<input class='form-control' type='text' name='options[".$name."]' value='' style='border:0px;width:auto;height:20px;float:right;color:#b2906a;font-weight:bold;float:left;' required='required'>
//        <div class='product_sizes_content' style='display: block;'>";
//        $inner_html.="<span data-info='product_color' class='product_sizes'>";
//        foreach ( $items as $item=>$value) {
//            $color = "<a href='javascript:return;' class='product_color'
//            style='border-radius: 25px;width: 50px;height: 50px;background-color:".$item.";border:0px;'
//            pic_link='$value'
//            name='options[".$name."]' onclick='clickLinksOption(this,\"color_img\",\"options[".$name."]\");'></a>";
//            $inner_html.=$color;
//        }
//        $inner_html.="</span></div>";
//        return$inner_html;
//    }
//    function builderOther($name,&$items) {
//        $inner_html = "<label style='text-transform: uppercase;font-weight: bold;float:left'>".$name."</label>";
//        $inner_html.="<input class='form-control' type='text' name='options[".$name."]' value='' style='border:0px;width:auto;height:20px;float:right;color:#b2906a;font-weight:bold;float:left;' required='required'>
//        <div class='product_sizes_content' style='display: block;'>";
//        $inner_html.="<span data-info='product_color' class='product_sizes'>";
//        foreach ( $items as $item=>$value) {
//            $color = "<a href='javascript:return;' name='options[Size]' onclick='clickOption(this,\"options[".$name."]\");'>".$item."</a>";
//            $inner_html.=$color;
//        }
//        $inner_html.="</span></div>";
//        return$inner_html;
//    }
    $json = json_encode($options);
    echo $json;