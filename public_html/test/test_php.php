<?php
    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:45
     */
    require_once ('../includes/utils/u_utils.php');
    $sql = "select * from %s where id=%d and isTage=%d";
    $parameters = array();
    $parameters[0] = "lc_categories";
    $parameters[1] = 2;
    $parameters[2] = 3;
    $sql = u_utils::builderSQL($sql,array('lc_categories',2,'4'));
    echo "import string:".$sql;