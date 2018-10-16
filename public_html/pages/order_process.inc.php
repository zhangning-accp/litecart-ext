<?php
    /**
     * 订单处理。这里做支付，以及支付成功后等操作
     */
  header('X-Robots-Tag: noindex');
  document::$layout = 'checkout';

  if (settings::get('catalog_only_mode')) {
      return;
  }

  $shipping = new mod_shipping();
    //创建payment支付集合。
  $payment = new mod_payment();
  // 判断是否有订单
  if (empty(session::$data['order'])) {
    notices::add('errors', 'Missing order object');
    header('Location: '. document::ilink('checkout'));
    exit;
  }

  $order = &session::$data['order'];

  //订单验证
  if ($error_message = $order->validate()) {
    notices::add('errors', $error_message);
    header('Location: '. document::ilink('checkout'));
    exit;
  }

// If Confirm Order button was pressed
  if (isset($_POST['confirm_order'])) {
    //ob_start()在服务器打开一个缓冲区来保存所有的输出。所以在任何时候使用echo，输出都将被加入缓冲区中，直到程序运行结束或者使用ob_flush()来结束。
      //然后在服务器中缓冲区的内容才会发送到浏览器，由浏览器来解析显示。这里使用ob_start() 应该是在53行做了header处理，如果没有
      // ob_start,header可能会出错.（猜测）
    ob_start();
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_customer.html.inc.php');
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_shipping.html.inc.php');
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_payment.html.inc.php');
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_summary.html.inc.php');
    // 函数ob_end_clean会清除缓冲区的内容，并将缓冲区关闭，但不会输出内容。
      //此时得用一个函数ob_get_contents()在ob_end_clean()前面来获得缓冲区的内容
    ob_end_clean();
    if (!empty(notices::$data['errors'])) {
      header('Location: '. document::ilink('checkout'));
      exit;
    }
    // 判断是否选中了支付方式，如paypal，或银行卡等。如果没有，则推出当前脚本运行，并给出错误提示。
    if (!empty($payment->modules) && count($payment->options()) > 0) {
      if (empty($payment->data['selected'])) {
        notices::add('errors', language::translate('error_no_payment_method_selected', 'No payment method selected'));
        header('Location: '. document::ilink('checkout'));
        exit;
      }

        //TODO: 增加Credit Card Information信息
        $order->data['card'] = array(
            'CurrCode' => '840', // * 货币代码 CHAR(8)
            'CardPAN' => $_POST['CardPAN'],// * 卡号 CHAR(16)
            'ExpirationMonth' => $_POST['ExpirationMonth'], // * 有效期月份 CHAR(2)
            'ExpirationYear' => $_POST['ExpirationYear'], // * 有效期年份 CHAR(2)
            'CVV2' => $_POST['CVV2'], // * 卡片安全码 CHAR(3) 其实就是CardPAN后3位数字
        );
      // pament做预检查。具体检查什么需要看payment的实现。如果有问题，则推出当前页面脚本运心
      if ($payment_error = $payment->pre_check($order)) {
        notices::add('errors', $payment_error);
        header('Location: '. document::ilink('checkout')."?pre_check=0");
        exit;
      }
        // 看当前页面有没有订单备注，如果有，则放入到order对象里。
      if (!empty($_POST['comments'])) {
        $order->data['comments']['session'] = array(
          'author' => 'customer',
          'text' => $_POST['comments'],
        );
      }
      // 获得payment的 transfer返回，如果返回里哟error，则退出，否则根据返回结构进行处理
      if ($gateway = $payment->transfer($order)) {
            //接受网关参数后，发送支付请求？还是应该verify验证后再发？
        if (!empty($gateway['error'])) {
          notices::add('errors', $gateway['error']);
          header('Location: '. document::ilink('checkout'));
          exit;
        }

        switch (@strtoupper($gateway['method'])) {
            // 这个部分需要根据具体情况进行修改。
          case 'POST'://如果为post，那么会将fields里的组织成一个隐藏表单并提交。
              /*----------------------------------------------------------------------------------------------
                以下$from 的html结构类似：
                <p>Redirecting...</p>
                <form name="gateway_form" method="post" action="http://www.paymentgateway.com/form_process.ext">
                  <input type="hidden" name="cancel_url" value="http://localhost/litecart/public_html/en/checkout" />
                  <input type="hidden" name="success_url" value="http://localhost/litecart/public_html/en/order_process" />
                  <input type="hidden" name="callback_url" value="http://localhost/litecart/public_html/en/ext/payment_service_provider/my_external_callback_file.php?order_uid=5bb0d76907087" />
                </form>
                <script>
                  document.forms["gateway_form"].submit();
                </script>
               */
              $from = "";
              $from =  '<p>'. language::translate('title_redirecting', 'Redirecting') .'...</p>' . PHP_EOL
               . '<form name="gateway_form" method="post" action="'. (!empty($gateway['action']) ? $gateway['action'] : document::ilink('order_process')) .'">' . PHP_EOL;
            if (is_array($gateway['fields'])) {
              foreach ($gateway['fields'] as $key => $value) {
                  //$from .= '  ' . functions::form_draw_hidden_field($key, $value) . PHP_EOL;
                  $from .= '  ' . functions::form_draw_hidden_field($key, $value) . PHP_EOL;
              }
            } else {
                $from.= $gateway['fields'];
            }
              $from .= '</form>' . PHP_EOL;//. '<script>' . PHP_EOL;
            if (!empty($gateway['delay'])) {
                $from .= '  var t=setTimeout(function(){' . PHP_EOL
                 . '    document.forms["gateway_form"].submit();' . PHP_EOL
                 . '  }, '. ($gateway['delay']*1000) .');' . PHP_EOL;
            } else {
                $from.= '  document.forms["gateway_form"].submit();' . PHP_EOL;
            }
              $from .= '</script>';
            echo $from;
            exit;

          case 'HTML':// 如果是html，则将 content里的html输出到页面。这里可以由用户自定义
            echo $gateway['content'];
            require_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_INCLUDES . 'app_footer.inc.php');
            exit;
            case "API_CALL": {// 表示第三方支付api的调用，返回的标准数据格式。这个原框架没有。属于后面加上的部分
                /*$return_array = array(
                    'method' => 'API_CALL',
                    "status"=>"1" or "0", 1:成功。 0：失败
                    "msg"=>"msg content"
                );*/
                if ($gateway['status'] === 1) {// 成功跳转到成功页面
                    $order_success_ilink = document::ilink('order_success');
                    header('Location: ' . $order_success_ilink);
                } else {
                    // 进入check_out，并给出提示
                    notices::add('errors', $gateway['msg']);
                    header('Location: ' . document::ilink('checkout'));
                    exit;
                }
                exit;
            }
            case 'GET'://如果是get请求，则提让浏览器重定向到指定的action。如果该
          default:
              $location = "";
              if(!empty($gateway['action'])) {
                  $location = $gateway['action'];
              } else {
                  $location = document::ilink('order_process');
              }
            header('Location: '. $location);
            exit;
        }
      } else {
         // exit;// 这里表示如果
      }
    }
  }// Confirm Order end...

//    if(!empty($_GET['pre_check'])){
//      exit;
//    }
    // 上一步如果不是提交订单，则进入订单验证环节。Verify transaction 上面都已经exit了，这里怎么能执行？
  if (!empty($payment->modules) && count($payment->options()) > 0) {
    $result = $payment->verify($order);
  // If payment error ，这里很奇怪，怎么会进入这里。
    if (!empty($result['error'])) {
      if (!empty($order->data['id'])) {
        $order->data['comments'][] = array(
          'author' => 'system',
          'text' => 'Payment Error: '. $result['error'],
          'hidden' => true,
        );
        //$order->save();//保存订单？
      }
      notices::add('errors', $result['error']);
      header('Location: '. document::ilink('checkout'));
      exit;
    }

  // Set order status id
    if (isset($result['order_status_id'])) {
        $order->data['order_status_id'] = $result['order_status_id'];
    }

  // Set transaction id
    if (isset($result['transaction_id'])) {
        $order->data['payment_transaction_id'] = $result['transaction_id'];
    }
  }

// Save order
  //$order->save();

// Clean up cart
  //cart::clear();

// Send emails
//  $email = $order->data['customer']['email'];
    // $order->email_order_copy($email);//没有发送成功。

  // zn 2018-10-11 annotation
//  foreach (preg_split('#(\s+)?;(\s+)?#', settings::get('email_order_copy')) as $email) {
//    $order->email_order_copy($email, settings::get('store_language_code'));
//  }

// Run after process operations
//  $shipping->after_process($order);
//
//  $payment->after_process($order);
//
//  $order_process = new mod_order();
//  $order_process->after_process($order);

//  $order_success_ilink = document::ilink('order_success');
//  header('Location: '. $order_success_ilink);
  exit;
