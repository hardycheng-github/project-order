<?php
/*
  $Id: postoff.php,v 1.1 2003/10/19 08:27:22 oldpa Exp $

  TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
  based on: 
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/

  class postoff {
    var $code, $title, $description, $enabled;

// class constructor
    function postoff() {
      global $order;

      $this->code = 'postoff';
      $this->title = MODULE_PAYMENT_POSTOFF_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_POSTOFF_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_POSTOFF_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_POSTOFF_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_POSTOFF_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_POSTOFF_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    
      $this->email_footer = MODULE_PAYMENT_POSTOFF_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order,$db;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_POSTOFF_ZONE > 0) ) {
        $check_flag = false;
        $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_POSTOFF_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while (!$check->EOF) {
          if ($check->fields['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check->fields['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
	$check->MoveNext();
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_POSTOFF_TEXT_DESCRIPTION1);
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
	global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_POSTOFF_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
	global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_POSTOFF_STATUS', 'True', '6', '1', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_POSTOFF_ACCNAME', '', '6', '1', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_POSTOFF_ACCNUM', '', '6', '1', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_POSTOFF_SORT_ORDER', '0', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_POSTOFF_ZONE', '0', '6', '2', 'twe_get_zone_class_title', 'twe_cfg_pull_down_zone_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_POSTOFF_ORDER_STATUS_ID', '0', '6', '0', 'twe_cfg_pull_down_order_statuses(', 'twe_get_order_status_name', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_POSTOFF_ALLOWED', '',   '6', '0', now())");
    }

    function remove() {
	global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_POSTOFF_STATUS', 'MODULE_PAYMENT_POSTOFF_ALLOWED', 'MODULE_PAYMENT_POSTOFF_ACCNAME', 'MODULE_PAYMENT_POSTOFF_ACCNUM', 'MODULE_PAYMENT_POSTOFF_ZONE', 'MODULE_PAYMENT_POSTOFF_ORDER_STATUS_ID', 'MODULE_PAYMENT_POSTOFF_SORT_ORDER');
    }
  }
?>