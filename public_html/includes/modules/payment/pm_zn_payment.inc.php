<?php

    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/9/29
     * Time: 13:44
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
         * 预检查，用来检查什么，暂时不知。
         */
        public function pre_check($order) {
            //echo __CLASS__."-pre_check-i:".PHP_EOL;
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
        public function transfer($order) {
            // 可以在这里直接处理支付，并不返回任何信息，但是错误怎么获取并提示？
            //echo __CLASS__."-transfer-i:".PHP_EOL;
            //$order->save(); // Save session order to database before transaction creates an $order->data['id']. Not recommended
            $fields = array(
              'OrderID' => $order->data['uid'], // * 订单号 CHAR(30)
              'CartID' => '', // * 购物车编码 CHAR(30)
              'CurrCode' => '840', // * 货币代码 CHAR(8)
              'Amount' => $order->data['payment_due'], // * 交易金额 CHAR(20)
              'CardPAN' => '',// * 卡号 CHAR(16)
              'ExpirationMonth' => '', // * 有效期月份 CHAR(2)
              'ExpirationYear' => '', // * 有效期年份 CHAR(2)
              'CVV2' => '', // * 卡片安全码 CHAR(3) 其实就是CardPAN后3位数字
              'IPAddress' => '', // * 持卡人的 ip 地址 CHAR(20)
              'CName' => $order->data['customer']['firstname'].$order->data['customer']['lastname'], // * 收货人姓名 CHAR(16)
              'BAddress' => $order->data['customer']['address1'], // * 收货人地址 CHAR(32)
              'BCity' => $order->data['customer']['city'], // * 收货人所在城市 CHAR(32)
              'Bstate' => '', // * 收货人州（省） CHAR(32)
              'Bcountry' => $order->data['customer']['country_code'], // * 收货人国家 CHAR(32)
              'BCountryCode' =>$order->data['customer']['country_code'], // * 国家代码  CHAR(2)
              'PostCode' => $order->data['customer']['postcode'], // * 邮编 CHAR(32)
              'Email' => $order->data['customer']['email'], // * 邮箱地址 CHAR(32)
              'Telephone' => $order->data['customer']['phone'], // * 电话号码 CHAR(20)
              'Pname' => 'other', // *产品和品牌 CHAR(32)
              'IFrame' => 1, // * 是否内嵌框架    CHAR(1)
              'URL' => 'http://www.baidu.com', // * 商户网站网址  CHAR(32)
              'OrderUrl' => 'http://www.baidu.com', // * 同 URL  CHAR(32)
              'callbackUrl' => '',//document::link(WS_DIR_TEST.pp_callback.php),// option 用于更新网店订单状态的地址   CHAR(50)
              'Framework' => 'litecart', // * 网店框架类型    CHAR(20)
              'IVersion' => 'V8.0', // * 版本    CHAR(10)
              'Language' => 'en', // * 语言(国际化)  CHAR(2)
              'HashValue' => '' // * 加密数据   CHAR(500)
            );
            $hash_value = u_utils::hash_value('8030jfewfrwe98qwer',
                '7998h8h5',$fields['OrderID'],$fields['Amount']);
            $fields['HashValue'] = $hash_value;
            // TODO：这里直接支付，支付成功后看能不能回调处理订单问题。
            // 发送请求。
//            $post_url = "https://merchant.paytos.com/CubePaymentGateway/gateway/action.NewSubmitAction.do";
//            $post_str = u_utils::sendHttpRequest($post_url,"POST",$fields);
//            echo  $post_str;

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