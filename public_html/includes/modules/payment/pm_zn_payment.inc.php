<?php

    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/29
     * Time: 13:44
     *
     * 通过查看order_process.inc.php的逻辑，方法的调用顺序为
     * pre_check->transfer->
     */
    class pm_zn_payment
    {
        public $id = __CLASS__;
        public $name = 'ZN Cash on Delivery';
        public $description = '';
        public $author = 'ZN';
        public $version = '1.0';
        public $support_link = 'http://localhost';
        public $website = 'http://www.litecart.net';
        public $priority = 0;

        public function __construct() {
           // echo __CLASS__."__construct-i:".PHP_EOL;
        }

        /**-------------------------------------------------------------------------
            Called in checkout used to display all avaliable payment options.
            A payment module may output several payment options if necessary i.e. card, directbank, etc.
            这里当用户在订单要进行支付时，页面右边会显示的支付平台的信息，比如说title
         */
        public function options($items, $subtotal, $tax, $currency_code, $customer) {
            return array(
                'title' => 'My Payment module',
                'options' => array(
                    array(
                        'id' => 'method1',
                        'icon' => 'images/payment/mymodule-method1.png',
                        'name' => 'Method 1',
                        'description' => 'Select this option for method 1.',
                        'fields' => '',
                        'cost' => 0,
                        'tax_class_id' => 0,
                        'confirm' => 'Confirm Order',
                    ),
                )
            );
        }

        /**
         * 预检查，用来检查什么，暂时不知。可以检查订单完整性等。返回boolean | 错误信息
         * 在 order_process.inc.php 第 61行。
         */
        public function pre_check($order) { // 检查必填项是否具备，如果不具备，则返回
            //echo __CLASS__."-pre_check-i:".PHP_EOL;
            //因为原框架会检查页面的其它输入项，目前还买找到怎么采用和框架一致的解决方案，所以这里先对Credit Card Information项做检查
//            $order->data['card'] = array(
//                'CurrCode' => '840', // * 货币代码 CHAR(8)
//                'CardPAN' => $_POST['CardPAN'],// * 卡号 CHAR(16)
//                'ExpirationMonth' => $_POST['ExpirationMonth'], // * 有效期月份 CHAR(2)
//                'ExpirationYear' => $_POST['ExpirationYear'], // * 有效期年份 CHAR(2)
//                'CVV2' => $_POST['CVV2'], // * 卡片安全码 CHAR(3) 其实就是CardPAN后3位数字
//            );
            $CurrCode = $order->data['card']['CurrCode'];
            $CardPAN = $order->data['card']['CardPAN'];
            $ExpirationMonth = $order->data['card']['ExpirationMonth'];
            $ExpirationYear = $order->data['card']['ExpirationYear'];
            $CVV2 = $order->data['card']['CVV2'];
            $error = false;
            if(u_utils::checkEmpty($CurrCode,$CardPAN,$ExpirationMonth,$ExpirationYear,$CVV2)) {
                $error = "Please check your credit card number or CVV2/CVC2/CAV2 ";
            }
            return $error;
        }

        /** ----------------------------------------------------------------------------------------
        The transfer() method is used to send the user to a payment gateway.
        The return is an array of the destination and transaction data.
        If not declared the transfer operation will be skipped.
         * 转账（）方法用于将用户发送到支付网关。
           返回是目的地和交易数据的数组。
           如果没有声明，传输操作将被跳过。
         * @return array
         * 参看 public_html/pages/order_process.inc.php  66 line进行理解
         */
        public function transfer(&$order) {
           //return $this->payment($order);
            /*-----------------------测试时用----------------------------*/
              return $this->simulationPayment($order);
            /**--------------------------------------------------------*/

            //echo  $post_str;
//            $order_success_ilink = document::link(WS_DIR_TEST.'payment_test.php');
//            header('Location: '. $order_success_ilink);
//        return array(
//            'action' => "https://merchant.paytos.com/CubePaymentGateway/gateway/action.NewSubmitAction.do",//document::link(WS_DIR_TEST."payment_test.php"),
//            'method' => 'POST',
//            'fields' => $fields,
//        );

//            $myview = new view();
//            $myview->snippets = array(
//                'fields' => $fields,
//            );
//
//            // 注意，这里不需要写inc.php,sttch会做处理
//            $html = $myview->stitch('views/box_payment_check');
//
//            return array(
//                'method' => 'html',
//                'content' => $html,
//            );

  }
        private function payment(&$order) {
            $amount = $order->data['payment_due'];
            $amount = intval($amount * 100);
            $expiration_year = $order->data['card']['ExpirationYear'];
            if(strlen($expiration_year) > 2) {
                $expiration_year = substr($expiration_year,-2);
            }
            $merchant_key = "yihuifu-liwenjie11753371";// 商户密钥
            $merchant_transaction_number = "yihuifu-liwenjie1111681"; // 商户交易号
            $hash_value = u_utils::hashValue($merchant_key, $merchant_transaction_number,$order->data['uid'],$amount);
            // 支付参数
            $fields = array(
                'AcctNo' =>$merchant_transaction_number,
                'OrderID' =>$order->data['uid'],
                'CartID' =>$order->data['uid'],
                'CurrCode' =>$order->data['card']['CurrCode'],
                'Amount' =>$amount,
                'IPAddress' =>'45.76.74.87',
                'Telephone' =>$order->data['customer']['phone'],
                'CardPAN' =>$order->data['card']['CardPAN'],
                'CName' =>$order->data['customer']['firstname'].$order->data['customer']['lastname'],
                'ExpirationMonth' =>$order->data['card']['ExpirationMonth'],
                'ExpirationYear' =>$expiration_year,
                'CVV2' =>$order->data['card']['CVV2'],
                'BAddress' =>$order->data['customer']['address1'],
                'BCity' =>$order->data['customer']['city'],
                'PostCode' =>$order->data['customer']['postcode'],
                'Email' =>$order->data['customer']['email'],
                'Bstate' =>'Nevada(NE)',
                'Bcountry' =>$order->data['customer']['country_code'],
                'BCountryCode' =>$order->data['customer']['country_code'],
                'IFrame' =>"1",
                'URL' =>$_SERVER['SERVER_NAME'],
                'OrderUrl' =>$_SERVER['SERVER_NAME'],
                'PName' =>'other',
                'HashValue' =>$hash_value,
                'Framework' =>'litecart',
                'IVersion' =>'V8.0',
                'Language' =>'en'
            );
            // TODO：这里直接支付，支付成功后对订单进行处理，并返回true，失败后也对订单进行处理，返回false，由order_process来决定处理方式。
            // 发送请求。
            $post_url = "https://merchant.paytos.com/CubePaymentGateway/gateway/action.NewSubmitAction.do";
            $post_str = json_decode(u_utils::sendHttpRequest($post_url,"POST",$fields),true);
            $return_array = array(
                'method' => 'API_CALL',
                "status"=>1,
                "msg"=>"Payment successed."
            );
            // 保存订单，保存后不用考虑清空购物车等情况。在其它地方会处理。如果保存失败呢？

            if($post_str['status'] === '0000'){
                // 表示成功
                $return_array['status'] = 1;
                $return_array['msg'] = 'Payment successed.';
                // 保存订单相关信息
//                1 Awaiting payment
//                2 Pending
//                3 Processing
//                4 Dispatched
//                5 Cancelled
                $order_status_id = 3;
                $order->data['order_status_id'] = $order_status_id;
                $order->save();
                // Send email 发送邮件
                $email = $order->data['customer']['email'];
                $order->email_order_copy($email);//没有发送成功。
                // Clear Shopping Cart 清空购物车等。
                cart::clear();// 清空购物车
            }else if($post_str['status'] !== '0000') {// 表示失败
                $return_array['status'] = 0;
                $return_array['msg'] = $post_str['msg'];
                // 修改订单状态？
                //notices::add();
                trigger_error($return_array['msg'],E_USER_ERRORR);
            }
            return $return_array;
        }
        private function simulationPayment($order) {
//            $order_status_id = 3;
//            $order->data['order_status_id'] = $order_status_id;
//            $order->save();
//            cart::clear();// 清空购物车
            //Send email 发送邮件
            $email = $order->data['customer']['email'];
            $order->email_order_copy($email);//没有发送成功。
            /*-----------------------------------------*/
            $return_array = array(
                'method' => 'API_CALL',
                "status"=>1,
                "msg"=>"Payment successed."
            );
            return $return_array;
        }
        /** --------------------------------------------------------------------------------------
        The verify() method is used to verify the transaction. There are a few security questions you may ask yourself:
        Does the transaction result come from a trusted source?
        Is this a valid order ID or UID
        Is the payment flagged as okay by the payment service provider?
        Is the payment amount the same as the order amount? Be aware of rounding.
        Is the payment currency same as the order currency?
         * verify（）方法用于验证交易。你可能会问自己一些安全问题：
            事务结果是否来自可信的来源？
            这是一个有效的订单ID还是UID
            支付服务提供商的支付是否被标记为ok？
            付款金额与订单金额相同吗？注意四舍五入。
            支付货币与订单货币相同吗？
         * @return array
         */

        public function verify($order) {
            // Verify some data
            //echo __CLASS__."-verify-i:".PHP_EOL;
//            if (isset($error) && $error == true) {
//                return array('error' => 'There was an error verifying your transaction');
//            }
//            return array(
//                'order_status_id' => $this->settings['order_status_id'],
//                'payment_transaction_id' => '123456789',
//                'comments' => 'This is an order comment',
//            );
        }
//        public function verify() {
//            return array(
//                'order_status_id' => $this->settings['order_status_id'],
//                'payment_transaction_id' => '',
//                'errors' => '',
//            );
//        }

        /**
         * This method does not have a return. It is available for after order operations if necessary i.e.
         * updating order reference with the order number.
         */
        public function after_process($order) {
           // echo __CLASS__."-after_process-:".PHP_EOL;
        }


        /** ------------------------------------------------------------------------------
         * This method returns html code that is output on the order success page.
         * It was intended to display a payment receipt but your imagination sets the limit.
         * @param $order
         */
        public function receipt($order) {
            //echo __CLASS__."-receipt-i:".PHP_EOL;
        }

        /**
         * This method sets up the payment module with a settings structure. The return is an array of the structure.
         * @return array
         * 在admin payment module里点击module时页面显示的内容
         */
        function settings() {
            return array(
                array(
                    'key' => 'status',
                    'default_value' => '0',
                    'title' => 'Status',
                    'description' => 'Enables or disables the module.',
                    'function' => 'toggle("e/d")',
                ),
                array(
                    'key' => 'icon',
                    'default_value' => 'images/payment/'.__CLASS__.'.png',
                    'title' => 'Icon',
                    'description' => 'Web path of the icon to be displayed.',
                    'function' => 'input()',
                ),
                array(
                    'key' => 'order_status_id',
                    'default_value' => '0',
                    'title' => 'Order Status:',
                    'description' => 'Give successful orders made with this payment module the following order status.',
                    'function' => 'order_status()',
                ),
                array(
                    'key' => 'priority',
                    'default_value' => '0',
                    'title' => 'Priority',
                    'description' => 'Process this module in the given priority order.',
                    'function' => 'int()',
                ),
            );
        }

        /**
         * This method does not have a return. It is executed upon installing the module in the admin panel.
         * It can be used for creating third party mysql tables etc. Note: install() doesn't run until the “Save” button is clicked.
         */
        public function install() {
            //echo __CLASS__."-install-i:".PHP_EOL;
        }

        /**
         * This method does not have a return. It is executed upon uninstalling the module in the admin panel.
         * It can be used for deleting orphan data.
         */
        public function uninstall() {
           // echo __CLASS__."-uninstall-i:".PHP_EOL;
        }

        /**
         * This method does not have a return. It is executed upon saving the module settings in the admin panel.
         * This method is triggered when the module is already installed.
         * It can be used for updating third party mysql tables etc.
         * Note: update() doesn't run until the “Save” button is clicked.
         */
        public function update() {
           // echo __CLASS__."-update-i:".PHP_EOL;
        }
    }