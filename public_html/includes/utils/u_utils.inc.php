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
        public static function builderSQL($format_sql, $parameter_values = array())
        {
            return vsprintf($format_sql, $parameter_values);
        }

        /**
         * 获取格式化的日期字符串。
         * @return false|string
         *
         */
        public static function getYMDHISDate($format = 'Y-m-d H:i:s')
        {
            return date($format);
        }

        /**
         * 获得数组指定索引的值。
         * @param $array 数字索引数组
         * @param $index 需要取值的索引
         * @return mixed 返回索引指定的值，如果索引越界，返回"";
         */
        public static function getArrayIndexValue($array, $index)
        {
            if ($index <= count($array) - 1) {
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
        public static function startWith($start_str, $str)
        {
            $start_str_length = strlen($start_str);
            $start_str_tmp = substr($str, 0, $start_str_length);
            if ($start_str_tmp === $start_str) {
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
         * return string or false. if $merchant_key or $merchant_transaction_number is empty return fasle;
         *
         */
        public static function hashValue($merchant_key, $merchant_transaction_number,
                                         $order_number, $transaction_amount, $currency_code = '840')
        {
            if (empty($merchant_key) || empty($merchant_transaction_number)) {
                return false;
            }
            $input = $merchant_key . $merchant_transaction_number . $order_number . $transaction_amount . $currency_code;
            $md5hex = md5($input);
            $len = strlen($md5hex) / 2;
            $md5raw = "";
            for ($i = 0; $i < $len; $i++) {
                $md5raw = $md5raw . chr(hexdec(substr($md5hex, $i * 2, 2)));
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
        public static function sendHttpRequest($url, $method = 'post', $params = array(), $timeout = 60)
        {
            $client = new http_client();
            $response_str = $client->call($method, $url, $params);

            return $response_str;
//            if(empty($method)) {
//                $method = "get";
//            }
//            $context = array ();
//            if (is_array ( $params )) {
//                ksort ( $params );
//                $context ['http'] = array (
//                    'timeout' => 60,
//                    'method' => strtoupper($method),
//                    'header' => "Content-type: application/x-www-form-urlencoded ",
//                    'content' => http_build_query( $params, '', '&' )
//                );
//            }
//            return file_get_contents ( $url, false, stream_context_create($context));
        }

        /**
         * 该方法检查一组参数里是否有空的数据，如果有返回true，如果都不为空 返回false；
         * @param array ...$paramters
         * @return bool
         */
        public static function checkEmpty(...$paramters)
        {
            $isEmpty = false;
            foreach ($paramters as $item) {
                $isEmpty = empty($item);
                if ($isEmpty == true) {
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
        public static function uniqidReal($lenght = 13)
        {
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
         * @param bool $opt 是否保留生成的guid中{}符号。true表示保留，false表示不保留
         * @return mixed|string
         * 如果 $isNotHR 为true，得到一个32个字符的guid字符串：C525A3C3B0194218B3D70026CE7CDDEB
         * 如果$isNotHR 为false，得到一个36个字符的guid字符串:64886563-5C7E-448D-86E9-DDC759DCEA71
         */
        public static function guid($isNotHR = true, $opt = false)
        {
            $guid = "";
            if (function_exists('com_create_guid')) {
                $guid =  com_create_guid();
            } else {
                mt_srand((double)microtime() * 10000);    // optional for php 4.2.0 and up.
                $charid = strtoupper(md5(uniqid(rand(), true)));
                $hyphen = chr(45);    // "-"
                $left_curly = $opt ? chr(123) : "";     //  "{"
                $right_curly = $opt ? chr(125) : "";    //  "}"
                $uuid = $left_curly
                    . substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12)
                    . $right_curly;

                $guid =  $uuid;
            }
            if($opt === false) {
                $guid = trim($guid, "{}");
            }
            if ($isNotHR === true) {
                $guid = str_replace("-", "", $guid);
            }
            return $guid;
        }

        /**
         *生成订单号，格式uid4Y2M2D2H2m2s
         * uid为13位(大写)，示例：7303CE22FA662。
         * 整个长度为27位 示例：0DA8F895A699820181014025824
         */
        public static function orderNumber()
        {
            // 格式： 时间 YYYYMMDDHHmmssuid。 其中uid为13位的长度
            $orderNo = date("YmdHis");
            $orderNo = strtoupper(self::uniqidReal()) . $orderNo;

            return $orderNo;
        }

        /**
         * 解压zip文件
         * @param $zipSource zip源文件
         * @param $unZipFloder 解压目录
         * @return  true or false
         *  Returns true on success or false on failure.
         */
        public static function unZip($zipSource, $unZipFloder)
        {
            $zip = new ZipArchive();
            $result = $zip->open($zipSource);
            if ($result === true) {
                $result = $zip->extractTo($unZipFloder);
            }

            return $result;
        }

        /**
         * 获取文件类型，该方法还未实现。 暂不可用。
         * @param $filePath
         * 格式：
         *  text/plain; charset=utf-8
         * application/zip; charset=binary
         * image/jpeg; charset=binary
         * image/webp; charset=binary
         * image/png; charset=binary
         */
        public static function getFileMIME($filePath)
        {
            $fileInfo = finfo_open(FILEINFO_MIME);
            $fileMime = finfo_file($fileInfo, $filePath);
            finfo_close($fileInfo);

            return $fileMime;
        }

        /**
         * 创建多级目录,
         * @param $dirPath
         * @return true or false
         * 如果目录不存在则创建，存在则不创建
         */
        public static function mkdirs($dirPath)
        {
            if (!file_exists($dirPath)) {
                return mkdir($dirPath, 0777, true);
            }
        }

        public static function files($folder)
        {
            $files = scandir($folder);
            foreach ($files as $key => $value) {
                if ($value == '.' || $value == '..') {
                    unset($files[$key]);
                }
            }

            return $files;
        }

        public static function deleteFile($filePath)
        {
            return unlink($filePath);
        }

        /**
         * 整理数据，将data和head处理后返回一个新的数据，新数据每行都由head->value表示
         * @param $head
         * @param $data
         */
        public static function disposalData($head, $data)
        {
            $newData = array();
            for ($i = 0; $i < count($data); $i++) {
                $tmp = $data[$i];
                $key_value_array = array();
                for ($j = 0; $j < count($tmp); $j++) {
                    if ($head[0][$j] !== $tmp[$j]) {
                        $key_value_array[$head[0][$j]] = $tmp[$j];
                    } else {
                        break;
                    }
                }
                $newData[$i] = $key_value_array;
            }
            if (empty($newData[0])) {
                unset($newData[0]);
            }

            return $newData;
        }
    }