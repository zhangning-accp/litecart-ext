<?php

    /**
     * email class
     * Class email
     */
  class email_old {
    private $_charset = 'UTF-8';
    private $_sender = array();//
    private $_recipients = array();//收件人
    private $_subject = '';//主题
    private $_multiparts = array();//附件内容

    public function __construct($charset=null) {

      $this->_charset = $charset ? $charset : language::$selected['charset'];
      // 获取store_email 和 store_name
      $this->set_sender(settings::get('store_email'), settings::get('store_name'));

      return $this;
    }

    public function set_sender($email, $name=null) {

      $email = trim($email);

      if (!$this->validate_email_address($email)){
          trigger_error('Invalid email address ('. $email .')', E_USER_ERROR);
      }

      $this->_sender = array(
        'email' => filter_var(preg_replace('#^.*\s<([^>+])>$#', '$1', $email), FILTER_SANITIZE_EMAIL),
        'name' => $name ? $name : trim(trim(preg_replace('#^(.*)\s?<[^>]+>$#', '$1', $email)), '"'),
      );

      return $this;
    }

    public function set_subject($subject) {

      $this->_subject = trim(preg_replace('#\R#', '', $subject));

      return $this;
    }

    public function add_body($content, $html=false, $charset=null) {

      if (!$charset) {
          $charset = $this->_charset;
      }

      $this->_multiparts[] = 'Content-Type: '. ($html ? 'text/html' : 'text/plain') .'; charset='. $charset . "\r\n"
                           . 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n"
                           . trim($content);

      return $this;
    }

      /**
       * 添加附件
       * @param $file 文件路径
       * @param null $filename 文件名
       * @param bool $parse_as_string
       * @return $this
       */
    public function add_attachment($file, $filename=null, $parse_as_string=false) {

      if (!$filename) {
          $filename = pathinfo($file, PATHINFO_BASENAME);
      }

      $data = $parse_as_string ? $file : file_get_contents($file);

      $this->_multiparts[] = 'Content-Type: application/octet-stream' . "\r\n"
                           . 'Content-Disposition: attachment; filename="'. basename($filename) . '"' . "\r\n"
                           . 'Content-Transfer-Encoding: base64' . "\r\n\r\n"
                           . chunk_split(base64_encode($data)) . "\r\n\r\n";//使用 RFC 2045 语义格式化 $data

      return $this;
    }

      /**
       * 添加收件人
       * @param $email 收件人地址
       * @param null $name 称呼
       * @return $this
       */
    public function add_recipient($email, $name=null) {

      $email = trim($email);

      if (!$this->validate_email_address($email)) {
          trigger_error('Invalid email address ('. $email .')', E_USER_ERROR);
      }

      $this->_recipients[] = array(
        'email' => filter_var(preg_replace('#^.*\s<([^>]+)>$#', '$1', $email), FILTER_SANITIZE_EMAIL),
        'name' => $name ? $name : trim(trim(preg_replace('#^(.*)\s?<[^>]+>$#', '$1', $email)), '"'),
      );

      return $this;
    }

      /**
       * 格式化联系方式内容
       * @param $contact
       * @return string
       */
    public function format_contact($contact) {

      if (empty($contact['name'])) {
          return '<'. $contact['email'] .'>';
      }

      // 这里没搞懂为什么要这样
      if (strtoupper(language::$selected['charset']) == 'UTF-8') {
        return '=?utf-8?b?'. base64_encode($contact['name']) .'?= <'. $contact['email'] .'>';
    }

      if (strpos($contact['name'], '"') !== false || strpos($contact['name'], ',') !== false) {
          // addcslashes — 以 C 语言风格使用反斜线转义字符串中的 "字符
        return '"'. addcslashes($contact['name'], '"') .'" <'. $contact['email'] .'>';
      }

      return $contact['name'] .' <'. $contact['email'] .'>';
    }

    public function validate_email_address($email) {

      return preg_match('#^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$#', $email) ? true : false;
    }

      /**
       * 发送邮件
       * @return bool
       */
    public function send() {

    // Perpare headers
      $headers = array(
        'From' => $this->format_contact($this->_sender),
        'Reply-To' => $this->format_contact($this->_sender),
        'Return-Path' => $this->format_contact($this->_sender),
        'MIME-Version' => '1.0',
        'X-Mailer' => PLATFORM_NAME .' '. PLATFORM_VERSION,
      );
      // attachment boundary
      $multipart_boundary_string = '==Multipart_Boundary_x'. md5(time()) .'x';
      $headers['Content-Type'] = 'multipart/mixed; boundary="'. $multipart_boundary_string . '"' . "\r\n";
        // 将headers 里的value数据重新组织成key:value的存储格式
      array_walk($headers,
        function (&$v, $k) {
          $v = $k.': '.$v;
        }
      );

      // 转成字符串。每个元素之间用\r\n分隔
      $headers = implode("\r\n", $headers);

    // Prepare subject
      if (strtoupper(language::$selected['charset']) == 'UTF-8') {
          // 为什么要这样？
        $subject = '=?utf-8?b?'. base64_encode($this->_subject) .'?=';
      } else {
        $subject = $this->subject;
      }

    // Prepare body
      $body = '';
      foreach ($this->_multiparts as $multipart) {
          $body .= '--'. $multipart_boundary_string . "\r\n"
                 . $multipart . "\r\n\r\n";
      }

    // Deliver via SMTP
      if (settings::get('smtp_status')) {

        $smtp = new smtp(
          settings::get('smtp_host'),
          settings::get('smtp_port'),
          settings::get('smtp_username'),
          settings::get('smtp_password')
        );

        foreach ($this->_recipients as $recipient) {

          $data = 'To: ' . $this->format_contact($recipient) . "\r\n"
                . 'Subject: ' . $subject . "\r\n"
                . $headers . "\r\n"
                . $body;
          try {
            $result = $smtp->send($this->_sender['email'], $recipient['email'], $data);
          } catch(Exception $e) {
            trigger_error('Failed sending email to '. $recipient['email'] .': '. $e->getMessage(), E_USER_WARNING);
          }
        }

        $smtp->disconnect();

    // Deliver via PHP mail()
      } else {

        foreach ($this->_recipients as $recipient) {
          if (!$result = mail($this->format_contact($recipient), $subject, $body, $headers)) {
            trigger_error('Failed sending email to '. $recipient['email'], E_USER_WARNING);
          }
        }
      }

      return !empty($result) ? true : false;
    }
  }
