<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:45
     */
    require_once ('../includes/utils/u_utils.inc.php');
    require_once ('../includes/classes/email.inc.php');

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
    echo  "Test sending email....";
    $email = new email();
    $email->add_recipient("909704945@qq.com")   // 收件人
    ->set_subject("Test sending email")         // 主题
    ->add_body("Hi ZN, How are you!")            // 邮件内容
    ->send();

?>