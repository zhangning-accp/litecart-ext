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

    ob_start();
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_customer.html.inc.php');
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_shipping.html.inc.php');
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_payment.html.inc.php');
    include_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_PAGES . 'ajax/checkout_summary.html.inc.php');
    ob_end_clean();

    if (!empty(notices::$data['errors'])) {
      header('Location: '. document::ilink('checkout'));
      exit;
    }
    // 判断是否选中了支付方式
    if (!empty($payment->modules) && count($payment->options()) > 0) {
      if (empty($payment->data['selected'])) {
        notices::add('errors', language::translate('error_no_payment_method_selected', 'No payment method selected'));
        header('Location: '. document::ilink('checkout'));
        exit;
      }

      if ($payment_error = $payment->pre_check($order)) {
        notices::add('errors', $payment_error);
        header('Location: '. document::ilink('checkout'));
        exit;
      }

      if (!empty($_POST['comments'])) {
        $order->data['comments']['session'] = array(
          'author' => 'customer',
          'text' => $_POST['comments'],
        );
      }
      // 这里返回的geteway 是干什么用的。
      if ($gateway = $payment->transfer($order)) {
            //接受网关参数后，发送支付请求？还是应该verify验证后再发？
        if (!empty($gateway['error'])) {
          notices::add('errors', $gateway['error']);
          header('Location: '. document::ilink('checkout'));
          exit;
        }

        switch (@strtoupper($gateway['method'])) {
            // 这个部分需要根据具体情况进行修改。
          case 'POST':
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
                //$from.= '  document.forms["gateway_form"].submit();' . PHP_EOL;
            }
              //$from .= '</script>';
            echo $from;
            exit;

          case 'HTML':
            echo $gateway['content'];
            require_once vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_INCLUDES . 'app_footer.inc.php');
            exit;

          case 'GET':
          default:
            header('Location: '. (!empty($gateway['action']) ? $gateway['action'] : document::ilink('order_process')));
            exit;
        }
      }
    }
  }

// Verify transaction 上面都已经exit了，这里怎么能执行？
  if (!empty($payment->modules) && count($payment->options()) > 0) {
    $result = $payment->verify($order);

  // If payment error
    if (!empty($result['error'])) {
      if (!empty($order->data['id'])) {
        $order->data['comments'][] = array(
          'author' => 'system',
          'text' => 'Payment Error: '. $result['error'],
          'hidden' => true,
        );
        $order->save();
      }
      notices::add('errors', $result['error']);
      header('Location: '. document::ilink('checkout'));
      exit;
    }

  // Set order status id
    if (isset($result['order_status_id'])) $order->data['order_status_id'] = $result['order_status_id'];

  // Set transaction id
    if (isset($result['transaction_id'])) $order->data['payment_transaction_id'] = $result['transaction_id'];
  }

// Save order
  $order->save();

// Clean up cart
  cart::clear();

// Send emails
  $order->email_order_copy($order->data['customer']['email']);

  foreach (preg_split('#(\s+)?;(\s+)?#', settings::get('email_order_copy')) as $email) {
    $order->email_order_copy($email, settings::get('store_language_code'));
  }

// Run after process operations
  $shipping->after_process($order);

  $payment->after_process($order);

  $order_process = new mod_order();
  $order_process->after_process($order);

  header('Location: '. document::ilink('order_success'));
  exit;
