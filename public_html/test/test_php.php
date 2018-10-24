<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:45
     */
    require_once ('../includes/utils/u_utils.inc.php');
    require_once ('../includes/library/lib_csvreader.inc.php');

//    $sql_in = "(";
//    $group_values = array("n1","n2","n3");
//    $sql_in .= "'" . join("','", array_values($group_values) ) . "'";
//    $sql_in .= ")";
//    $sql_in = u_utils::builderSQL($sql_in, array("lc_product_groups_info"));
//    echo "import string:".$sql_in;

//    $sql = "price:%.2f,%F";
//    $sql = u_utils::builderSQL($sql,array(4443564565.78,56,799));
//    echo $sql;
//        $names = "23-5,45-5,";
//    //$names = substr($names,0,-1);
//    $lastIndex = strripos($names,",");
//    $count = strlen($names);
//    if($lastIndex === $count -1 ) {
//        $names = substr($names,0,-1);
//        echo "names:" . $names.PHP_EOL;
//    }
//    echo "lastIndex:". $lastIndex ." count:".$count." name:".$names.PHP_EOL;
//    $str = "https://zhidao.baidu.com/question/1835338391241062820.html?qbl=relate_question_0&word=php%20%C8%E7%BA%CE%B1%C8%BD%CF%C1%BD%B8%F6%D7%D6%B7%FB%B4%AE";
//    echo "is :" . u_utils::startWith("http1",$str).PHP_EOL;

//    for($i=0;$i<10;$i++) {
//        $orderNo = u_utils::orderNo();
//        echo "orderNo:".$orderNo."</br>";
//    }

    $zipFile = "C:\\Users\\zn\\Desktop\\import-export-test\\test-case\\litecart_test_csv\\litecart_test_csv.csv";
    $test = new csvreader($zipFile);
    $data_tmp = array();
    //$count = $test->get_lines();
    for($j = 0; $j< 150;$j++) {
        $start = 10000 * $j;
        $data = $test->get_data(10000, $start);
        if(empty($data)) {
            break;
        }
        foreach ($data as $key => $value) {
            foreach($value as $vk=>$vv) {
                if($vk == 3) {
                    $data_tmp[$vv] = $vv;
                    break;
                }
            }
        };
        echo 'rows:$j--'.count($data_tmp).'</br>';
    }
    echo count($data_tmp);
    //echo u_utils::getFileMIME($zipFile);
?>