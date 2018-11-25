<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:45
     */
    require_once ('../includes/utils/u_utils.inc.php');


//    require_once ('../includes/library/lib_csvreader.inc.php');
//    echo 'start...';
//    $file = 'D:\\zip-test\\litecart_2018-11-01/383a974c137f202a62478b54b9d9481c.csv';
//    $folder = "D:\\zip-test\\litecart_2018-11-01/";
//    u_utils::splitDataToFile2($file,10000,1000,$folder);
//    u_utils::zip("c:/logs/logs.zip",u_utils::fileAbsolutePaths($folder));
//    $s1 = "#-[【SHOP FOR:Men's|SU'B DEPARTMENT:Limited|COLOR/STYLE:Black";
//    $s2 = "SHOP FOR:Men's|SUB DEPARTMENT:Limited|COLOR/STYLE:Black";
//    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-links:x.jpg|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
//    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-links:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
//    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
//    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-j:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
//    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
//    $s1 = "style:XL";// 导致异常
//    $s1 = "style:XL-links:10:";
//    $s1 = "style:XL-links:10.jpg";// none
//    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl:1-:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";


    //    $s1 = u_utils::fliterWords($s1);
//    $s1 = u_utils::parseOptionGroup($s1);
//    foreach ($s1 as $name=>$values) {
//        foreach ($values as $value) {
//            $v1 = $v;
//        }
//    }
//    echo var_dump($s1);
//    $FILE_TYPE = $_FILES['file']['type'];//上传的文件类型
//    $TMP_FILE = $_FILES['file']['tmp_name'];//临时文件路径
//    $TMP_FOLDER = dirname($tmp_file);// 得到临时存放文件的目录

    //$UN_ZIP_FOLDER = "";// zip解压目录路径
    //1. 临时目录里有common目录，同时系统目录里没有common
    //2. 临时目录里有common目录，系统目录里也有common目录
//    $op_str = "Style:Classic-links:common/Classic.jpg|Color:#FFFFFF,#efce1f,#50af72-links:Classic/WhiteTshirt.png,Classic/DaisyTshirt.png,Classic/IrishGreenTshirt.png|Size:SM,MED";
    //3.  临时目录里有common目录，但没有数据里指定的文件，系统目录没有common
    //4. 临时目录里有common目录，但没有数据里指定的文件，系统目录有common和指定的文件
    //5. 临时目录里没有common目录，同时系统目录里有common目录且有相同的文件
//    $op_str = "Style:Classic-links:common/Classic_no.jpg|Color:#FFFFFF,#efce1f,#50af72-links:Classic/WhiteTshirt.png,Classic/DaisyTshirt.png,Classic/IrishGreenTshirt.png|Size:SM,MED";
    // 有几个路径：1. 文件解压路径。2. 系统common路径。 3. 产品的图片路径
//    $op_str = "Style:Classic-links:common/Classic.jpg|Color:#FFFFFF,#efce1f,#50af72-links:c/WhiteTshirt.png,Classic/DaisyTshirt.png,Classic/IrishGreenTshirt.png|Size:SM,MED";
//    $op_str = "Style:Classic-links:common/Classic.jpg|Color:#FFF,#efce1f,#d5ab47,#50af72-links:Classic/WhiteTshirt.png,Classic/DaisyTshirt.png,Classic/GoldTshirt.png,Classic/IrishGreenTshirt.png|Size:X SM (Youth),SM (Youth),MED (Youth),SM,MED,LG,XL,2XL,3XL,4XL,5XL,6XL";
//    $product_info = array(
//        "option_groups"=>$op_str,
//        "option_groups_array"=>array()
//    );
//    $option_group_array = u_utils::parseOptionGroup($op_str);
//
//
//    global $path;
//    $path = "path";
//    processSource($product_info);
//    function processSource(&$product_info) {
//        global $path;
//        $path = "dsfads";
//        $DEFAULT_FOLDER = "D:/zip-test";
//        $TMP_FOLDER = $DEFAULT_FOLDER."/tmp/";
//        $WEB_SITE_FOLDER = $DEFAULT_FOLDER."/images/";
//        $WEB_SITE_COMMON_FOLDER = $WEB_SITE_FOLDER."common/";
//
//        $product_code = "D6DEAC6704A745D8B45F6933BCB77ED6";
//        $product_folder = $TMP_FOLDER.$product_code."/";
//        if(!u_utils::exists($product_folder,false)) {
//            u_utils::mkdirs($product_folder);
//        }
//        $option_group_str = $product_info['option_groups'];
//        $option_group_array = u_utils::parseOptionGroup($option_group_str);
//        $product_info["option_groups_array"] = $option_group_array;
//        foreach ($option_group_array as $option_group_name => $values) {
//            foreach($values as $value=>$link) {
//                if(!empty($link)) { //1. 判断是否有links
////                  是否是common开头
//                    $isCommon = u_utils::startWith("common",$link);
//                    if($isCommon) {
//                        echo $link."是common文件";
//                        $tmp_link = $product_folder.$link;
//                        if(u_utils::exists($tmp_link)) {// 检查解压目录里是否有该文件
//                            // 存在，复制到网站common目录
//                            if(!u_utils::exists($WEB_SITE_COMMON_FOLDER,false)) {
//                                u_utils::mkdirs($WEB_SITE_COMMON_FOLDER);
//                            }
//                            $isCopy = copy($tmp_link,$WEB_SITE_COMMON_FOLDER.basename($link));
//                            // 这里的link要修改路径，因为拷贝过去后，就是系统里的link了。而不是临时目录的link
//                            $product_info["option_groups_array"][$option_group_name][$value] = $link;
//                            echo $tmp_link."存在解压目录common里</br>";
//                        } else {// 解压目录没有此图片，到网站默认的common路径去找是否存在此图片
//                            $link = $WEB_SITE_FOLDER.$link;
//                            if(u_utils::exists($link)) {
//                                echo $link."存在系统common里</br>";
//                                // 存在
//                                $product_info["option_groups_array"][$option_group_name][$value] = $link;
//                            } else {
//                                //不处理
//                                echo $link."不存在在系统common里</br>";
//                            }
//                        }
//                    } else {// 非common资源
//                        echo $link."是非common资源</br>";
////                        $unzip_folder = $product_folder;
//                        $tmp_link = $product_folder.$link;
//                        $web_site_product = $WEB_SITE_FOLDER.$product_code."/";
//                        $link_parent = dirname($link,1);
//                        if($link_parent !== "\\" && $link_parent !== "." && $link_parent !== ".." ) {
//                            $web_site_product = $web_site_product.$link_parent."/";
//                        }
//
//                        // 到解压目录下去找该文件
//                        if(u_utils::exists($tmp_link)) {
//
//                            // 拷贝到网站目录下。目录名是 product_code.
//                            if(!u_utils::exists($web_site_product,false)) {
//                                u_utils::mkdirs($web_site_product);
//                            }
//
//                            $isCopy = copy($tmp_link,$web_site_product.basename($tmp_link));
//                            $product_info["option_groups_array"][$option_group_name][$value] = $link;
//                            echo $link."解压目录里存在，并拷贝到".$web_site_product.basename($tmp_link)."/</br>";
//                        } else {
//                            // 不处理 如果没找到，到对应的网站目录下去找，
//
//                            if(u_utils::exists($web_site_product.basename($tmp_link))) {
//                                $product_info["option_groups_array"][$option_group_name][$value] = $web_site_product.basename($tmp_link);
//                                echo $link."解压目录里不存在，在".$web_site_product."里找到了</br>";
//                            } else {
//                                echo $link."解压目录里不存在，".$WEB_SITE_FOLDER.$product_code." 里也没有</br>";
//                            }
//                        }
//                    }
//                } else {//没有links 第一版不处理没有links的情况
//                    echo "links is null</br>";
//                }
//            }
//        }
//        var_dump($product_info);
//    }
    $option_groups_array = array(
            "Style"=> array("Classic"=>"products/common/Classic.jpg"),
            "Color"=>array(
                "#FFF"=>"products/48da8abf437a492eb24d4b3d2121ea49/Classic/WhiteTshirt.png",
                "#efce1f"=>"products/48da8abf437a492eb24d4b3d2121ea49/Classic/DaisyTshirt.png",
                "#d5ab47"=>"products/48da8abf437a492eb24d4b3d2121ea49/Classic/GoldTshirt.png",
                "#50af72"=>"products/48da8abf437a492eb24d4b3d2121ea49/Classic/IrishGreenTshirt.png"
            ),
            "Size"=>array(
                "X SM (Youth)"=>"",
                "SM (Youth)"=>"",
                "MED (Youth)"=>"",
                "SM"=>"",
                "MED"=>"",
                "LG"=>"",
                "XL"=>"",
                "2XL"=>"",
                "3XL"=>"",
                "4XL"=>"",
                "5XL"=>"",
                "6XL"=>"")
        );

    if(!empty($option_groups_array)) {
        reset($option_groups_array);
        $rootOptionName = key($option_groups_array);// 拿到 Style
        $rootOptionValue = key($option_groups_array[$rootOptionName]); // 拿到Style
        echo $rootOptionValue;
//        foreach ($array as $option_name=>$option_values) {
//            $option_values = $array[$option_name];
////            $prev = u_utils::findArrayPrev($option_name,$array);
//            echo "option_name:".$option_name.",prev:".$prev."</br>";
//        }
    }

?>
