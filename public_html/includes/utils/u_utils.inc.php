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

        /**
         * 托付支付的HashValue 生成算法
         * @param $merchant_key 商户密钥
         * @param $merchant_transaction_number 商户交易号
         * @param $order_number 订单号
         * @param $transaction_amount 交易金额
         * @param $currency_code 币种 default='840' USD,美元
         * return string.
         */
        public static function hash_value($merchant_key,$merchant_transaction_number,
                                          $order_number,$transaction_amount,$currency_code = '840')
        {
            $input = $merchant_key."".$merchant_transaction_number."".$order_number."".$transaction_amount."".$currency_code;
            $md5hex = md5($input);
            $len = strlen($md5hex) / 2;
            $md5raw = "";
            for ($i = 0; $i < $len; $i++) {
                $md5raw = $md5raw . chr(hexdec(substr($md5hex, $i * 2,2)));
            }
            $keyMd5 = base64_encode($md5raw);

            return $keyMd5;
        }

        /**
         * 发送http请求
         * @param $url 请求的url
         * @param string $method 如果为空，则采用get方式发送请求。
         * @param array $params 参数列表
         * @param number $timeout 超时时间，单位秒。
         * @return bool|string 发送失败，则返回false。否则返回一个字符串。字符串内容由url服务提供方决定。
         */
        public static function sendHttpRequest($url,$method='post',$params=array(),$timeout=60) {
            if(empty($method)) {
                $method = "get";
            }
            $context = array ();
            if (is_array ( $params )) {
                ksort ( $params );
                $context ['http'] = array (
                    'timeout' => 60,
                    'method' => strtoupper($method),
                    'content' => http_build_query( $params, '', '&' )
                );
            }
            return file_get_contents ( $url, false, stream_context_create($context));
        }
    }