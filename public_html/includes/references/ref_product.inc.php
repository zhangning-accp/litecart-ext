<?php

  class ref_product {

    private $_id;
    private $_currency_code;
    private $_language_codes;
    private $_customer_id;
    private $_data = array();

    function __construct($product_id, $language_code=null, $currency_code=null, $customer_id=null) {

      $this->_id = (int)$product_id;
      $this->_language_codes = array_unique(array(
        !empty($language_code) ? $language_code : language::$selected['code'],
        settings::get('default_language_code'),
        settings::get('store_language_code'),
      ));
      $this->_currency_code = !empty($currency_code) ? $currency_code : currency::$selected['code'];
      $this->_customer_id = !empty($customer_id) ? $customer_id : customer::$data['id'];
    }

    public function &__get($name) {

      if (array_key_exists($name, $this->_data)) {
        return $this->_data[$name];
      }

      $this->_data[$name] = null;
      $this->_load($name);

      return $this->_data[$name];
    }

    public function &__isset($name) {
      return $this->__get($name);
    }

    public function __set($name, $value) {
      trigger_error('Setting data ('. $name .') is prohibited', E_USER_ERROR);
    }

    private function _load($field='') {

      switch($field) {

        case 'also_purchased_products':

          $this->_data['also_purchased_products'] = array();

            $query = database::query(
              "select oi.product_id, sum(oi.quantity) as total_quantity from ". DB_TABLE_ORDERS_ITEMS ." oi
              left join ". DB_TABLE_PRODUCTS ." p on (p.id = oi.product_id)
              where p.status
              and (oi.product_id != 0 and oi.product_id != ". (int)$this->_id .")
              and order_id in (
                select distinct order_id as id from ". DB_TABLE_ORDERS_ITEMS ."
                where product_id = ". (int)$this->_id ."
              )
              group by oi.product_id
              order by total_quantity desc;"
            );

            while ($row = database::fetch($query)) {
              $this->_data['also_purchased_products'][$row['product_id']] = reference::product($row['product_id']);
            }

          break;

        case 'name':
        case 'short_description':
        case 'description':
        case 'head_title':
        case 'meta_description':
        case 'attributes':

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_INFO ."
            where product_id = ". (int)$this->_id ."
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'product_id', 'language_code'))) continue;
              if (empty($this->_data[$key])) $this->_data[$key] = $value;
            }
          }

          break;

        case 'campaign':

          $this->_data['campaign'] = array();

          $products_campaigns_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_CAMPAIGNS ."
            where product_id = ". (int)$this->_id ."
            and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
            and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
            order by end_date asc
            limit 1;"
          );
          $products_campaign = database::fetch($products_campaigns_query);

          if (!empty($products_campaign)) {
            $this->_data['campaign'] = $products_campaign;
            if ($products_campaign[$this->_currency_code] > 0) {
              $this->_data['campaign']['price'] = (float)currency::convert($products_campaign[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
            } else {
              $this->_data['campaign']['price'] = (float)$products_campaign[settings::get('store_currency_code')];
            }
          }

          break;

        case 'categories':

          $this->_data['categories'] = array();

          $products_to_categories_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_TO_CATEGORIES ."
            where product_id = ". (int)$this->_id .";"
          );

          while ($product_to_category = database::fetch($products_to_categories_query)) {
            $categories_info_query = database::query(
              "select * from ". DB_TABLE_CATEGORIES_INFO ."
              where category_id = '". (int)$product_to_category['category_id'] ."'
              and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
              order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
            );

            while ($row = database::fetch($categories_info_query)) {
              foreach ($row as $key => $value) {
                if (in_array($key, array('id', 'category_id', 'language_code'))) continue;
                if (empty($this->_data['categories'][$product_to_category['category_id']])) $this->_data['categories'][$product_to_category['category_id']] = $value;
              }
            }
          }

          break;

        case 'default_category':

          $this->_data['default_category'] = false;

          if (empty($this->default_category_id)) return;

          $this->_data['default_category'] = reference::category($this->default_category_id);

          break;

        case 'delivery_status':

          $this->_data['delivery_status'] = array();

          $query = database::query(
            "select * from ". DB_TABLE_DELIVERY_STATUSES_INFO ."
            where delivery_status_id = '". (int)$this->_data['delivery_status_id'] ."'
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'delivery_status_id', 'language_code'))) continue;
              if (empty($this->_data['delivery_status'][$key])) $this->_data['delivery_status'][$key] = $value;
            }
          }

          break;

        case 'images':

          $this->_data['images'] = array();
          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_IMAGES." where product_id = ". (int)$this->_id ." order by priority asc, id asc;"
          );
          while ($row = database::fetch($query)) {
            $this->_data['images'][$row['id']] = $row['filename'];
          }

          break;

        case 'manufacturer':

          $this->_data['manufacturer'] = array();

          if (empty($this->_data['manufacturer_id'])) return;

          $this->_data['manufacturer'] = reference::manufacturer($this->manufacturer_id);

          break;

        case 'options': {// find product options info

            $this->_data['options'] = array();

            // 先查询商品有哪些options. 这里还是要做区别，否则拿到的数据不对。这里确实需要重新组织数据
            $sql = "select * from " . DB_TABLE_PRODUCTS_OPTIONS . " where product_id = " . (int)$this->_id . " order by priority;";
            $products_options_query = database::query($sql);

            while ($product_option = database::fetch($products_options_query)) {// 遍历每一条记录
                // Group
                if (!isset($this->_data['options'][$product_option['group_id']]['id'])) {
                    // 查询这些规格属于哪个group
                  $sql = "select * from " . DB_TABLE_OPTION_GROUPS . " where id = '" . (int)$product_option['group_id'] . "' limit 1;";
                  $option_group_query = database::query($sql);
                  $option_group = database::fetch($option_group_query);
                  foreach (array('id', 'function', 'required') as $key) {
                      // 将'id', 'function', 'required'放到对应的group_id 下
                    $this->_data['options'][$product_option['group_id']][$key] = $option_group[$key];
                  }
                }

                if (!isset($this->_data['options'][$product_option['group_id']]['name'])) {
                    // 查询组的name信息
                    $sql = "select * from " . DB_TABLE_OPTION_GROUPS_INFO . " as pcgi
                where group_id = '" . (int)$product_option['group_id'] . "'
                and language_code in ('" . implode("', '", database::input($this->_language_codes)) . "')
                order by field(language_code, '" . implode("', '", database::input($this->_language_codes)) . "');";
                    $option_group_info_query = database::query($sql);
                    while ($option_group_info = database::fetch($option_group_info_query)) {
                        foreach ($option_group_info as $key => $value) {
                            if (in_array($key, array('id', 'group_id', 'language_code'))) {
                                // 不添加id,group_id,language_code 到 group_id 下
                                continue;
                            }
                            if (empty($this->_data['options'][$product_option['group_id']][$key])) {
                                $this->_data['options'][$product_option['group_id']][$key] = $value;
                            }
                        }
                    }
                }

                // Values
                if (!isset($this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['id'])) {
                    // 查询具体的option value
                  $sql = "select * from " . DB_TABLE_OPTION_VALUES . " where id = '" . (int)$product_option['value_id'] . "' limit 1;";
                  $option_value_query = database::query($sql);
                  $option_value = database::fetch($option_value_query);
                  foreach (array('id', 'value') as $key) {
                    $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']][$key] = $option_value[$key];
                  }
                }

                if (!isset($this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['name'])) {
                    // 查询value_id对应的name
                    $sql = "select * from " . DB_TABLE_OPTION_VALUES_INFO . " as pcvi
                    where value_id = '" . (int)$product_option['value_id'] . "'
                    and language_code in ('" . implode("', '", database::input($this->_language_codes)) . "')
                    order by field(language_code, '" . implode("', '", database::input($this->_language_codes)) . "');";
                    $option_values_info_query = database::query($sql);
                    while ($option_value_info = database::fetch($option_values_info_query)) {
                        foreach ($option_value_info as $key => $value) {
                            if (in_array($key, array('id', 'value_id', 'language_code'))) {
                                // 排除'id', 'value_id', 'language_code'字段数据
                                continue;
                            }
                            // _data=>options=>{group_id=>{values=>{id=>{}}}}
                            if (empty($this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']][$key])) {
                                $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']][$key] = $value;
                            }
                        }
                    }
                    // 为 具体的规格添加links数据
                    // 查tree表，如果有，则使用该表里的links
                    $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['links'] = $product_option['links'];
                }

                // Price Adjust
                $product_option['price_adjust'] = 0;

                if ((isset($product_option[$this->_currency_code]) && $product_option[$this->_currency_code] != 0) || (isset($product_option[settings::get('store_currency_code')]) && $product_option[settings::get('store_currency_code')] != 0)) {

                    switch ($product_option['price_operator']) {
                        case '+':
                            if ($product_option[$this->_currency_code] != 0) {
                                $product_option['price_adjust'] = currency::convert($product_option[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                            } else {
                                $product_option['price_adjust'] = $product_option[settings::get('store_currency_code')];
                            }
                            break;
                        case '%':
                            if ($product_option[$this->_currency_code] != 0) {
                                $product_option['price_adjust'] = $this->price * ((float)$product_option[$this->_currency_code] / 100);
                            } else {
                                $product_option['price_adjust'] = $this->price * $product_option[settings::get('store_currency_code')] / 100;
                            }
                            break;
                        case '*':
                            if ($product_option[$this->_currency_code] != 0) {
                                $product_option['price_adjust'] = $this->price * $product_option[$this->_currency_code];
                            } else {
                                $product_option['price_adjust'] = $this->price * $product_option[settings::get('store_currency_code')];
                            }
                            break;
                        default:
                            trigger_error('Unknown price operator for option', E_USER_WARNING);
                            break;
                    }
                }

                $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['price_adjust'] = $product_option['price_adjust'];

            }// while end ....

            break;
        }

        case 'options_stock':

          $this->_data['options_stock'] = array();

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
            where product_id = ". (int)$this->_id ."
            ". (!empty($option_id) ? "and id = '". (int)$option_id ."'" : '') ."
            order by priority asc;"
          );

          while ($row = database::fetch($query)) {

            if (empty($row['tax_class_id'])) {
              $row['tax_class_id'] = $this->tax_class_id;
            }

            if (empty($row['sku'])) {
              $row['sku'] = $this->sku;
            }

            if (empty($row['weight']) || $row['weight'] == 0) {
              $row['weight'] = $this->weight;
              $row['weight_class'] = $this->weight_class;
            }

            if (empty($row['dim_x'])) {
              $row['dim_x'] = $this->dim_x;
              $row['dim_y'] = $this->dim_y;
              $row['dim_z'] = $this->dim_z;
              $row['dim_class'] = $this->dim_class;
            }

            $row['name'] = array();
            $tmp = explode(',', $row['combination']);
            if(!empty($tmp)) {
                foreach ($tmp as $combination) {
                    if (!empty($combination)) {
                        list($group_id, $value_id) = explode('-', $combination);
                        $options_values_query = database::query(
                            "select * from " . DB_TABLE_OPTION_VALUES_INFO . "
                where value_id = '" . (int)$value_id . "'
                and language_code in ('" . implode("', '", database::input($this->_language_codes)) . "')
                order by field(language_code, '" . implode("', '", database::input($this->_language_codes)) . "');"
                        );

                        while ($option_value_info = database::fetch($options_values_query)) {
                            foreach ($option_value_info as $key => $value) {
                                if (in_array($key, array('id', 'value_id', 'language_code'))) continue;
                                if (empty($row[$key][$option_value_info['value_id']])) $row[$key][$option_value_info['value_id']] = $value;
                            }
                        }
                    }
                }
            }

            $row['name'] = implode(',', $row['name']);

            $this->_data['options_stock'][$row['id']] = $row;
          }

          break;

        case 'parents':

          $this->_data['parents'] = array();

          $query = database::query(
            "select category_id from ". DB_TABLE_PRODUCTS_TO_CATEGORIES ."
            where product_id = '". (int)$this->_id ."';"
          );

          while ($row = database::fetch($query)) {
            $this->_data['parents'][$row['category_id']] = reference::category($row['category_id']);
          }

          break;

        case 'price':

          $this->_data['price'] = 0;

          $products_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_PRICES ."
            where product_id = ". (int)$this->_id ."
            limit 1;"
          );
          $product_price = database::fetch($products_prices_query);

          if ($product_price[$this->_currency_code] != 0) {
            $this->_data['price'] = currency::convert($product_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['price'] = $product_price[settings::get('store_currency_code')];
          }

          break;

        case 'product_groups':

          $this->_data['product_groups'] = array();

          if (count($this->product_group_ids)) {
            foreach ($this->product_group_ids as $pair) {

              list($group_id, $value_id) = explode('-', $pair);

              $query = database::query(
                "select name from ". DB_TABLE_PRODUCT_GROUPS_INFO ."
                where product_group_id = '". (int)$group_id ."'
                and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
                order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
              );

              while ($row = database::fetch($query)) {
                if (empty($this->_data['product_groups'][$pair]['group'])) $this->_data['product_groups'][$pair]['group'] = $row['name'];
              }

              $query = database::query(
                "select name from ". DB_TABLE_PRODUCT_GROUPS_VALUES_INFO ."
                where product_group_value_id = '". (int)$value_id ."'
                and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
                order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
              );
              while ($row = database::fetch($query)) {
                if (empty($this->_data['product_groups'][$pair]['value'])) $this->_data['product_groups'][$pair]['value'] = $row['name'];
              }
            }
          }

          break;

        case 'quantity_unit':

          $this->_data['quantity_unit'] = array(
            'id' => null,
            'decimals' => 0,
            'separate' => false,
            'name' => '',
          );

          $quantity_unit_query = database::query(
            "select id, decimals, separate from ". DB_TABLE_QUANTITY_UNITS ."
            where id = ". (int)$this->quantity_unit_id ."
            limit 1;"
          );

          if (!database::num_rows($quantity_unit_query)) return;

          $this->_data['quantity_unit'] = database::fetch($quantity_unit_query);

          $query = database::query(
            "select * from ". DB_TABLE_QUANTITY_UNITS_INFO ."
            where quantity_unit_id = '". (int)$this->quantity_unit_id ."'
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );
          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'quantity_unit_id', 'language_code'))) continue;
              if (empty($this->_data['quantity_unit'][$key])) $this->_data['quantity_unit'][$key] = $value;
            }
          }

          break;

        case 'sold_out_status':

          $this->_data['sold_out_status'] = array();

          $query = database::query(
            "select id, orderable from ". DB_TABLE_SOLD_OUT_STATUSES ."
            where id = '". (int)$this->sold_out_status_id ."'
            limit 1;"
          );
          $this->_data['sold_out_status'] = database::fetch($query);

          if (empty($this->_data['sold_out_status'])) return;

          $query = database::query(
            "select * from ". DB_TABLE_SOLD_OUT_STATUSES_INFO ."
            where sold_out_status_id = '". (int)$this->_data['sold_out_status_id'] ."'
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'sold_out_status_id', 'language_code'))) continue;
              if (empty($this->_data['sold_out_status'][$key])) $this->_data['sold_out_status'][$key] = $value;
            }
          }

          break;

        default:

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS ."
            where id = ". (int)$this->_id ."
            limit 1;"
          );
          $row = database::fetch($query);

          if (database::num_rows($query) == 0) return;

          foreach ($row as $key => $value) {
            switch($key) {
              case 'product_groups':
                $this->_data['product_group_ids'] = explode(',', $row['product_groups']);
                break;

              case 'keywords':
                $this->_data[$key] = !empty($row[$key]) ? explode(',', $row[$key]) : array();
                break;

              default:
                $this->_data[$key] = $value;
                break;
            }
          }

          break;
      }
    }
  }
