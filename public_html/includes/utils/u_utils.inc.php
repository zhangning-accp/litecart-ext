<?php

    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:15
     */
    class u_utils
    {
        /**
         * 该方法将占位符sql 处理为有具体值的字符串。
         * @param $format_sql 带格式化的sql
         * @param $parameter_values 参数。
         * 如果$parameter_values 参数值个数和$format_sql 里的格式化字符个数不一致，则会出现错误。
         *
         */
        public static function builderSQL($format_sql,$parameter_values = array()) {
            return vsprintf($format_sql,$parameter_values);
        }

        /**
         * 获取格式化的日期字符串。
         * @return false|string
         *
         */
        public static function getYMDHISDate($format = 'Y-m-d H:i:s') {
            return date($format);
        }
        /**
         * 获得数组指定索引的值。
         * @param $array 数字索引数组
         * @param $index 需要取值的索引
         * @return mixed 返回索引指定的值，如果索引越界，返回"";
         */
        public static function getArrayIndexValue($array,$index) {
            if($index <= count($array) -1 ) {
                return $array[$index];
            } else {
                return "";
            }
        }

        /**
         * 判断一个字符串是否以$start_str代表的字符串开头。如果是返回true，否则返回false；
         * @param $start_str ： 开头的字符串
         * @param $str 要查找的字符串
         */
        public static function startWith($start_str,$str) {
            $start_str_length = strlen($start_str);
            $start_str_tmp = substr($str,0,$start_str_length);
            if($start_str_tmp === $start_str) {
                return true;
            }
            return false;
        }
    }