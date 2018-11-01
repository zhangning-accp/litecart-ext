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
    $file = 'C:\\Users\\zn\\Desktop\\import-export-test\\test-case\\litecart_test_csv/litecart_test_csv-50w-1-1.csv';
    $folder = "c:/logs/";
    u_utils::splitDataToFile2($file,10000,1000,$folder);
    echo 'end...';
    ?>
