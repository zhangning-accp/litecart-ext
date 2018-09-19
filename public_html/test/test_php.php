<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:45
     */
    require_once ('../includes/utils/u_utils.inc.php');

    $sql_in = "(";
    $group_values = array("n1","n2","n3");
    $sql_in .= "'" . join("','", array_values($group_values) ) . "'";
    $sql_in .= ")";
    $sql_in = u_utils::builderSQL($sql_in, array("lc_product_groups_info"));
    echo "import string:".$sql_in;