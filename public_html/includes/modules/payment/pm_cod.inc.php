<?php

  class pm_cod {
    public $id = __CLASS__;
    public $name = 'Cash on Delivery';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $support_link = 'http://www.litecart.net';
    public $website = 'http://www.litecart.net';
    public $priority = 0;

    public function __construct() {
       // echo __CLASS__."-__construct-i:".PHP_EOL;
    }

    /**-------------------------------------------------------------------------
     Called in checkout used to display all avaliable payment options.
     A payment module may output several payment options if necessary i.e. card, directbank, etc.
    */
    public function options($items, $subtotal, $tax, $currency_code, $customer) {

      if (empty($this->settings['status'])) return;

      $country_code = !empty($customer['shipping_address']['country_code']) ? $customer['shipping_address']['country_code'] : $customer['country_code'];

      if (!empty($this->settings['geo_zone_id'])) {
        if (functions::reference_in_geo_zone($this->settings['geo_zone_id'], $country_code, $customer['zone_code']) != true) return;
      }

      $method = array(
        'title' => language::translate(__CLASS__.':title_cash_on_delivery', 'Cash on Delivery'),
        'description' => '',
        'options' => array(
          array(
            'id' => 'cod',
            'icon' => $this->settings['icon'],
            'name' => reference::country($country_code)->name,
            'description' => '',
            'fields' => '',
            'cost' => $this->settings['fee'],
            'tax_class_id' => $this->settings['tax_class_id'],
            'confirm' => language::translate(__CLASS__.':title_confirm_order', 'Confirm Order'),
          ),
        )
      );
      return $method;
    }

    public function pre_check() {
       // echo __CLASS__."-pre_check-i:".PHP_EOL;
    }


      /** ----------------------------------------------------------------------------------------
        The transfer() method is used to send the user to a payment gateway.
        The return is an array of the destination and transaction data.
        If not declared the transfer operation will be skipped.
       * @return array
       */
    public function transfer() {//转账
//      return array(
//        'action' => '',
//        'method' => '',
//        'fields' => '',
//      );
       // echo __CLASS__."-transfer-i:".PHP_EOL;
    }

      /** --------------------------------------------------------------------------------------
          The verify() method is used to verify the transaction. There are a few security questions you may ask yourself:
          Does the transaction result come from a trusted source?
          Is this a valid order ID or UID
          Is the payment flagged as okay by the payment service provider?
          Is the payment amount the same as the order amount? Be aware of rounding.
          Is the payment currency same as the order currency?
        verify（）方法用于验证交易。你可能会问自己一些安全问题：
          1. 事务结果是否来自可信的来源？
          2. 这是一个有效的订单ID还是UID
          3. 支付服务提供商的支付是否被标记为ok？
          4. 付款金额与订单金额相同吗？注意四舍五入。
          5. 支付货币与订单货币相同吗？
       * @return array
       */
    public function verify() {
//      return array(
//        'order_status_id' => $this->settings['order_status_id'],
//        'payment_transaction_id' => '',
//        'errors' => '',
//      );
        //echo __CLASS__."-verify-i:".PHP_EOL;
    }

      /**
       * This method does not have a return. It is available for after order operations if necessary i.e.
       * updating order reference with the order number.
       * 该方法没有返回，。如有必要，它可用于订单操作，例如，使用订单号更新订单引用。
       */
    public function after_process() {
        //echo __CLASS__."-after_process-i:".PHP_EOL;
    }


      /** ------------------------------------------------------------------------------
       * This method returns html code that is output on the order success page.
       * It was intended to display a payment receipt but your imagination sets the limit.
       * 当支付成功后，该方法返回一个html页面，类似交易单。
       * @param $order
       */
    public function receipt($order) {
       // echo __CLASS__."-receipt-i:".PHP_EOL;
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
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_status', 'Status'),
          'description' => language::translate(__CLASS__.':description_status', 'Enables or disables the module.'),
          'function' => 'toggle("e/d")',
        ),
        array(
          'key' => 'icon',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_icon', 'Icon'),
          'description' => language::translate(__CLASS__.':description_icon', 'Web path of the icon to be displayed.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'fee',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_payment_fee', 'Payment Fee'),
          'description' => language::translate(__CLASS__.':description_payment_fee', 'Adds a payment fee to the order.'),
          'function' => 'decimal()',
        ),
        array(
          'key' => 'tax_class_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_tax_class', 'Tax Class'),
          'description' => language::translate(__CLASS__.':description_tax_class', 'The tax class for the fee.'),
          'function' => 'tax_classes()',
        ),
        array(
          'key' => 'order_status_id',
          'default_value' => '0',
          'title' => language::translate('title_order_status', 'Order Status'),
          'description' => language::translate('modules:description_order_status', 'Give orders made with this payment method the following order status.'),
          'function' => 'order_status()',
        ),
        array(
          'key' => 'geo_zone_id',
          'default_value' => '',
          'title' => language::translate('title_geo_zone_limitation', 'Geo Zone Limitation'),
          'description' => language::translate('modules:description_geo_zone', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
          'function' => 'geo_zones()',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate('title_priority', 'Priority'),
          'description' => language::translate('modules:description_priority', 'Process this module in the given priority order.'),
          'function' => 'int()',
        ),
      );
    }

      /**
       * This method does not have a return. It is executed upon installing the module in the admin panel.
       * It can be used for creating third party mysql tables etc.
       * Note: install() doesn't run until the “Save” button is clicked.
       * 这个方法没有返回，它是在在管理面板中安装模块时执行的。
       * 它可以用于创建第三方mysql表等。注意：install（）在点击“保存”按钮之前不会运行。
       */
    public function install() {
       // echo __CLASS__."-install-i:".PHP_EOL;
    }

      /**
       * This method does not have a return. It is executed upon uninstalling the module in the admin panel.
       * It can be used for deleting orphan data.
       * 这个方法没有返回值。它是在卸载管理面板中的模块时执行的。它可以用于删除孤儿数据。
       */
    public function uninstall() {
        //echo __CLASS__."-uninstall-i:".PHP_EOL;
    }

      /**
       * This method does not have a return. It is executed upon saving the module settings in the admin panel.
       * This method is triggered when the module is already installed.
       * It can be used for updating third party mysql tables etc.
       * Note: update() doesn't run until the “Save” button is clicked.
       *
       * 这个方法没有返回值。它是在管理面板中保存模块设置时执行的。这个方法是在模块已经安装时触发的。
       * 它可以用于更新第三方mysql表等。注意：update（）直到点击“保存”按钮时才会运行。
       */
    public function update() {
        //echo __CLASS__."-update-i:".PHP_EOL;
    }
  }
