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
         * @param $merchant_key 商户密钥 yihuifu-liwenjie1111681
         * @param $merchant_transaction_number 商户交易号 yihuifu-liwenjie11753371
         * @param $order_number 订单号
         * @param $transaction_amount 交易金额
         * @param $currency_code 币种 default='840' USD,美元
         * return string.
         *
         */
        public static function hashValue($merchant_key,$merchant_transaction_number,
                                          $order_number,$transaction_amount,$currency_code = '840')
        {
            if(empty($merchant_key)) {
                $merchant_key = 'yihuifu-liwenjie1111681';
            }
            if(empty($merchant_transaction_number)) {
                $merchant_transaction_number = "yihuifu-liwenjie11753371";
            }
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
                    'header' => "Content-type: application/x-www-form-urlencoded ",
                    'content' => http_build_query( $params, '', '&' )
                );
            }
            return file_get_contents ( $url, false, stream_context_create($context));
        }

        /**
         * 该方法检查一组参数里是否有空的数据，如果有返回true，如果都不为空 返回false；
         * @param array ...$paramters
         * @return bool
         */
        public static function checkEmpty(...$paramters) {
            $isEmpty = false;
            foreach ( $paramters as $item) {
                $isEmpty = empty($item);
                if($isEmpty == true) {
                    break;
                }
            }
            return $isEmpty;
        }

        /**
         * 生成唯一id
         * @param int $lenght id长度
         * @return bool|string
         * @throws Exception
         */
        public static function uniqidReal($lenght = 13) {
            // uniqid gives 13 chars, but you could adjust it to your needs.
            if (function_exists("random_bytes")) {
                $bytes = random_bytes(ceil($lenght / 2));
            } elseif (function_exists("openssl_random_pseudo_bytes")) {
                $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
            } else {
                throw new Exception("no cryptographically secure random function available");
            }

            return substr(bin2hex($bytes), 0, $lenght);
        }

        /**
         * 创建一个32 or 36 字符的guid，
         * @param bool $isNotHR 是否去掉guid中的 - 符号。 truea表示去除，false表示不去除
         * @return mixed|string
         * 如果 $isNotHR 为true，得到一个32个字符的guid字符串：C525A3C3B0194218B3D70026CE7CDDEB
         * 如果$isNotHR 为false，得到一个36个字符的guid字符串:64886563-5C7E-448D-86E9-DDC759DCEA71
         */
        public static function guid($isNotHR = true) {
            $guid = com_create_guid();
            if($isNotHR === true) {
                $guid = trim($guid,"{}");
                $guid = str_replace("-","",$guid);
            }
            return $guid;
        }

        /**
         *生成订单号，格式uid4Y2M2D2H2m2s
         * uid为13位(大写)，示例：7303CE22FA662。
         * 整个长度为27位 示例：0DA8F895A699820181014025824
         */
        public static function orderNumber() {
            // 格式： 时间 YYYYMMDDHHmmssuid。 其中uid为13位的长度
            $orderNo = date("YmdHis");
            $orderNo = strtoupper(self::uniqidReal()).$orderNo;
            return $orderNo;
        }
    }