<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/30
     * Time: 22:36
     */
    require_once ("../includes/config.inc.php");
    $serial_number = time();
    //echo $serial_number;
    $sign = $serial_number % 2;

    $success = array(
        "status"=>"0000",
        "msg"=>"success",
        "isPendingPayment"=>"false",
        "url"=>WS_DIR_TEST.'pp_callback.php',//回调地址
        "data"=>array(
            "orderStatus"=>"Pay for success",//订单状态
            "orderNo"=>null,//订单号
            "curr_Code"=>'USD',//货币代码
            "amount"=>'128.00',//金额
            "hashValue"=>'',
            "url"=>WS_DIR_TEST.'pp_callback.php',//回调地址
            "par1"=>'',//交易号
            "par2"=>'',//订单号
            "par3"=>$serial_number,//交易流水号
            "par4"=>"00",//返回的 code
            "par5"=>"succeed",//返回的信息
            "par6"=>"USD",//返回货币代码
        )
    );
    $failure = array(
        "status"=>"001",
        "msg"=>"false",
        "isPendingPayment"=>"true",
        "data"=>null,
        "url"=>WS_DIR_TEST.'pp_callback.php',//回调地址
    );
    if($sign == 0) {
        // 输出成功
        echo json_encode($success);
    } else {
        // 输出失败
        echo json_encode($failure);
    }

