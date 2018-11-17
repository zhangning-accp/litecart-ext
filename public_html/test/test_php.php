<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:45
     */
    require_once ('../includes/utils/u_utils.inc.php');
    require_once ('../includes/library/lib_csvreader.inc.php');
    echo 'start...';
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
//    $s1 = "style:XL,XXL,S,M,SL:1";
    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl:1-:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";


    //    $s1 = u_utils::fliterWords($s1);
    $s1 = u_utils::parseOptionGroup($s1);
    echo var_dump($s1);
    ?>
