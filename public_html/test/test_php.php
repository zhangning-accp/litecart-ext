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
    $product_info = array("price"=>"");
    $prices = $product_info['price'];
    /*异常情况检测*/
    if(empty($prices)) {
        $prices = "0.00|0.00";
    }
    if(u_utils::startWith("|",$prices)) {
        $prices = "0.00".$prices;
    }
    if(u_utils::endWith("|",$prices)) {
        $prices.="0.00";
    }
    // 获取[0]原价和[1]销售价
    $prices = preg_split("/[|]/",$prices);
    $product_info['prices'] = $prices;
    var_dump(floatval("d34"));
    ?>
