<?php

    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/14
     * Time: 10:15
     */
    class u_utils
    {
        private static $fliterWords = array(
            '`','·','~','!','！','@','#','$','￥','%','^','……','&','*','(',')','（',
            '）','-','_','——','+','=','|','\\','[',']','【','】','{','}',';','；',':','：','\'','"','“',
            ',','，','<','>','《','》','.','。','/','、','?','？');
        /**
         * 该方法将占位符sql 处理为有具体值的字符串。
         * @param $format_sql 带格式化的sql
         * @param $parameter_values 参数。
         * 如果$parameter_values 参数值个数和$format_sql 里的格式化字符个数不一致，则会出现错误。
         *
         */
        public static function builderSQL(&$format_sql, &$parameter_values = array())
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
        public static function startWith($start_str, $str="")
        {
            $start_str_length = strlen($start_str);
            $start_str_tmp = substr($str, 0, $start_str_length);
            if ($start_str_tmp === $start_str) {
                return true;
            }
            return false;
        }
        /**
         * 判断一个字符串是否以$start_str代表的字符串开头。如果是返回true，否则返回false；
         * @param $start_str ： 开头的字符串
         * @param $str 要查找的字符串
         */
        public static function endWith($end_str, $str)
        {
            $start_str_length = strlen($end_str);
            $start_str_tmp = substr($str, strlen($str)-$start_str_length);
            if ($start_str_tmp === $end_str) {
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
                $guid = com_create_guid();
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

                $guid = $uuid;
            }
            if ($opt === false) {
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
        public static function createOrderNumber()
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
         * 压缩文件，将一个或多个文件压缩位zip文件。改方法不支持目录压缩
         * @param $zipFilePath zip文件路径
         * @param array $filePaths 需要压缩的一个或多个文件
         */
        public static function zip($zipFilePath, $filePaths = array())
        {
            if (empty($filePaths)) {
                return;
            }
            $zip = new ZipArchive();
            $zip->open($zipFilePath, ZipArchive::CREATE);   //打开压缩包
            foreach ($filePaths as $file) {
                $zip->addFile($file, basename($file));   //向压缩包中添加文件
            }
            $zip->close();
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
            if (!is_dir($dirPath)) {
                return mkdir($dirPath, 0777, true);
            }
        }

        /**
         * 获取指定目录下的文件名列表。注意，改方法只会获得文件名，如果想获得文件的绝对路径，请使用fileAbsolutePaths方法
         * @param $folder
         * @return array
         */
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

        /**
         * @param $folder
         * @return array
         */
        public static function fileAbsolutePaths($folder)
        {
            $files = scandir($folder);
            foreach ($files as $key => $value) {
                if ($value == '.' || $value == '..') {
                    unset($files[$key]);
                } else {
                    $files[$key] = $folder . "/" . $value;
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
         * @param $head 表头二维数组，最有一个元素，第二维度代表表头
         * @param $data 数据数组，实际上是个二维数组，第维度代表行数，第二维度代表该行的所有列数据
         */
        public static function disposalData($head, $data)
        {
            $newData = array();
            $data_size = count($data);
            $head_size = count($head[0]);
            for ($i = 1; $i < $data_size; $i++) {//这里只所以从１开始，是因为data里包含了表头，这是php　splitFileObject的一个bug
                $tmp = $data[$i];// 获取d当前行的数据一维数组
                $tmp_size = count($tmp);// 拿到数据的列数
                // csv里的实际数据行的列有可能小于表头的列，这时为了避免数组越界，所以取最小值。同理，如果数据列数大于表头列，也取最少列值。
                if ($tmp_size < $head_size) {
                    $head_size = $tmp_size;
                }
                $key_value_array = array();
                for ($j = 0; $j < $head_size; $j++) {
                    if ($head[0][$j] !== $tmp[$j]) {
                        $key_value_array[$head[0][$j]] = $tmp[$j];
                    } else {
                        break;
                    }
                }
                $newData[$i - 1] = $key_value_array;
            }
//            if (empty($newData[0])) {
//                unset($newData[0]);
//            }

            return $newData;
        }

        /**
         * This method use unset($variate) and $variate = null.
         * @param $variate
         */
        public static function unsetVariate(&$variate)
        {
            unset($variate);
            $variate = null;
        }

        /**
         * 删除目录和目录下的所有子目录以及文件
         * @param $dirName 需要删除的目录路径
         */
        public static function deleteDirectoryAndFile($directoryPath)
        {
            if ($handle = opendir($directoryPath)) {// 打开目录句柄
                while (false !== ($item = readdir($handle))) {//从目录句柄中读取条目(改目录下的子文件或目录)
                    if ($item != "." && $item != "..") {
                        $child = $directoryPath . "/" . $item;
                        if (is_dir($child)) {//判断当前是否是一个目录,如果是则向下搜索。
                            self::deleteDirectoryAndFile($child);
                        } else {// 否则删除当前的文件
                            unlink($child);
                        }
                    }
                }
                closedir($handle);// 关闭句柄释放资源
                rmdir($directoryPath);//清除目录。
            }
        }

        /**
         * 将文件数据拆分读取后写入到新文件。 如果希望将大文件更简单的拆分成小文件，请使用 splitDataToFile2
         * @param int $length 读取的数据长度
         * @param int $start 读取的开始位置
         * @param $desc_file_path 目标文件路径
         */
        public static function splitDataToFile($data = array(), $desc_file_path = '')
        {
            $splitFile = new SplFileObject($desc_file_path, 'cb');
            $splitFile->fseek(0, SEEK_END);
            foreach ($data as $key => $value) {
                $splitFile->fputcsv($value);
            }
        }

        /**
         * 将大文件写成多个小文件。函数内部会自增index作为文件名的一部分。规则是 $file_prefix + index.csv 例如： sp_0.csv,sp_1.csv
         * @param $srcCSVFile 需要读取的原csv文件 注意，文件路径里不能有非英文内容
         * @param $split_rows 每个文件保存的数据数
         * @param $read_rows 每次从原csv一次读取的行数
         * @param string $desc_folder_path 目标文件存放的目录,最后的/可以有可以没有。
         * @param $file_prefix 保存的文件前缀。
         * @param  $isHead 是否每个文件需要表头。这个需要源文件具有表头。否则会将源文件的第1行所有列作为表头添加到每个文件。
         */
        public static function splitDataToFile2($srcCSVFile, $splitRows, $readRows, $descFolderPath = '',
                                                $filePrefix = 'sp_', $isHead = true)
        {
            if ($readRows < 1 || $splitRows < 1) {
                return;
            }
            $path = $srcCSVFile;
            $csv_file = new csvreader($path);
            $lines = $csv_file->get_lines();
            if($isHead == true) {
                $head = $csv_file->get_data(1, 1);// 获取表头数据
            }
            $outLoop = 0; // 外层大循环,一个大文件能被拆成几个小文件。
            if ($lines / $splitRows == 0) {
                $outLoop = $lines / $splitRows;
            } else {
                $outLoop = ($lines / $splitRows) + 1;
            }
            $outLoop = intval($outLoop);
            // 内层循环，一个小文件里有多少数据
            $innerLoop = 0;
            if ($splitRows / $readRows == 0) {
                $innerLoop = $splitRows / $readRows;
            } else {
                $innerLoop = ($splitRows / $readRows) + 1;
            }
            $innerLoop = intval($innerLoop);
            $start = 0;
            for ($index = 0; $index < $outLoop; $index++) {// 开始每个小文件的数据写入
                $desc = $descFolderPath."/";
                for ($i = 0; $i < $innerLoop; $i++) {
                    if (($start + $readRows) > $lines) {//如果最后的row大于剩下的行数，调整需要读取的行数，处理为有多少读多少。
                        $readRows = $lines - $start;
                    }
                    $data = $csv_file->get_data($readRows, $start);
                    if($index != 0 && $isHead == true && !empty($data) && count($data) > 1) {// 第一个文件本身就会读取到表头，所以是第一个之后的文件需要表头数据
                        array_splice($data, 0, 0, $head);// 添加表头到数据
                        $isHead = false;

                    }
                    if(!empty($data) && count($data) > 1) {
                        $start = $start + $readRows;//开始位置
                        u_utils::splitDataToFile($data, $desc . $filePrefix . $index . ".csv");
                    }

                }
                $isHead = true;
            }
        }

        /**
         * 检查文件或目录是否存在
         * @param string $filePath
         * @param bool $isFile 如果监测的是目录是否存在，该值为false
         * @return bool
         */
        public static function exists($filePath="",$checkFile=true) {
            if($checkFile) {
                return is_file($filePath);
            } else {
                return is_dir($filePath);
            }
        }

        /**
         * 用来替代系统的count函数，做了类型判断
         * @param $array_or_countable
         * @param int $mode
         * @return int
         */
        public static function count($array_or_countable,$mode = COUNT_NORMAL){
            if(is_array($array_or_countable) || is_object($array_or_countable)){
                return count($array_or_countable, $mode);
            }else{
                return 0;
            }
        }

        /**
         * 该方法会将$srcStr里的一些我们认为是非法的字符用""替换.如果想自己指定替换的符号，请使用fliterWordsTo
         * @param $srcStr 需要过滤的字符串
         * @return 返回替换后的字符串
         */
        public static function fliterWords($srcStr) {
            $srcStr = str_replace(self::$fliterWords,"",$srcStr);
            return $srcStr;
        }

        public static function fliterWordsTo(&$srcStr,$replace="") {
            $srcStr = str_replace(self::$fliterWords,$replace,$srcStr);
           return $srcStr;
        }

        /**
         * 对preg_split的一个封装。
         * @param $src
         * @param string $patternr 不用书写/xxx/界定符。函数会在首位加上。举例： [|]. 实际会变成 /[|]/
         * @return array
         */
        public static function split($src,$pattern="") {
            return preg_split("/".$pattern."/",$src);
        }

        /**
         * 解析$optionGroups 字符串为一个数组
         * @param $optionGroups 格式 style:1,2,3,4,5-links：1,2,3,4,5|size:x,xl-links：|color:dddf-links
         * @return array
         * 举例：    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-links:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
         * 结果：
         * {
            "style": {
                "XL": "XL.jpg",
                "XXL": "XXL.jpg",
                "S": "S.jpg",
                "M": "",
                "SL": ""
                },
            "size": {
                "x": "",
                "xl": ""
                },
            "color": {
                "#FF9900": "FF9900.jpg",
                "#3e5r6t": "#3e5r6t.jpg"
                }
            }
         * 测试用例以下用例都能正确得到结果：
        //    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-links:x.jpg|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
        //    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-links:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
        //    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
        //    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl-j:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
        //    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
        //    $s1 = "style:XL,XXL,S,M,SL:1";
        //    $s1 = "style:XL,XXL,S,M,SL-links:XL.jpg,XXL.jpg,S.jpg|size:x,xl:1-:|color:#FF9900,#3e5r6t-links:FF9900.jpg,#3e5r6t.jpg";
         */
        public static function parseOptionGroup($optionGroupStr) {
            // 0="style":[xl:common/1.jpg]
            // 1="color":[#ssss:1.jpg]
            // 格式：style:1,2,3,4,5-links：1,2,3,4,5|size:x,xl-links：|color:dddf-links
            // 如果是上传的文件里包含的图片links可以不用写。如果要引用已经存在的图片，使用http，或https，公共文件夹的只需要书写common即可。
            $optionGroupData = array();
            $Optioins = self::split($optionGroupStr,"[|]");// 拆分各个规格数据
            foreach ($Optioins as $option) {
                $childerns = self::split($option,"-");// 拆分规格和对应的links数据
                $values = array();
                $specification = array();
                $links = array();
                if(count($childerns) > 0) {
                    $specification = self::split($childerns[0],":");
                    $specification = array_filter($specification);
                    // 以下代码是为了应对一些特殊情况
                    if(count($specification) == 2) {
                        $specification[1] = explode(",", $specification[1]);
                    } else if(count($specification) > 2) {
                        unset($specification[count($specification) - 1]);
                        $specification[1] = explode(",", $specification[1]);
                    } else {
                        $specification[1] = "";
                    }

                    $specification[$specification[0]] = $specification[1];
                    unset($specification[0]);
                    unset($specification[1]);
                }
                if(count($childerns) > 1) {
                    $links = self::split($childerns[1],":");
                    if(count($links) == 2) {
                        $links[1] = explode(",", $links[1]);
                    } else {
                        $links[1] = "";
                    }
                    $links['links'] = $links[1];
                    unset($links[0]);
                    unset($links[1]);
                } else {
                    $links['links'] = array();
                }
                // 将规格和links 数据进行合并，合并为诸如这种格式： XL=xx.jpg,以规格为主。
                foreach ($specification as $key=>$specification_values) {
                    $link_values = $links['links'];
                    $specification_count = count($specification_values);
                    $links_count = count($link_values);
                    $loop = $specification_count > $links_count ? $specification_count : $links_count;
                    for($i = 0; $i<$loop; $i++) {
                        $value = $specification_values[$i];
                        unset($specification_values[$i]);
                        if($i >= count($link_values)) {
                            $specification_values[$value] = "";
                        } else {
                            $specification_values[$value] = $link_values[$i];
                        }
                    }
                    $optionGroupData[$key] = $specification_values;
                }
            }
            return $optionGroupData;
        }

        /**
         * @param $file 该方法获得一个文件名：默认包括后缀.
         */
        public static function haveFileName($file) {
            return basename($file);
        }

        /**
         * 找到上一个元素的名字。用于key-value的数组
         * @param $currentKey
         * @param $array
         * @return mixed|string
         */
        public static function findArrayPrev($currentKey,&$array) {
            if(empty($array)) {
                return "";
            }
            $tmpArray = array();
            $prevName = "";
            foreach ($array as $key=>$value) {
                $tmpArray[] = $key;
            }
            for ($i = 0; $i < self::count($tmpArray); $i ++) {
                $tmp = $tmpArray[$i];
                if($tmp === $currentKey && $i > 0) {
                    $prevName = $tmpArray[$i - 1];
                    break;
                }
            }
            return $prevName;
        }
        public static function findArrayNext($currentKey,&$array) {
            $next = 0;
            reset($array);
            do {
                $tmp_key = key($array);
                $res = next($array);
            } while ( ($tmp_key != $currentKey) && $res );
            if( $res ) {
                $next = key($array);
            }
            return $next;
        }
    }