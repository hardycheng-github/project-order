<?php
/* -----------------------------------------------------------------------------------------
   $Id: atm.php,v 1.1 2003/09/06 22:13:54 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cod.php,v 1.28 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (cod.php,v 1.7 2003/08/24); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

  class atm {
    var $code, $title, $description, $enabled;

// class constructor
    function atm() {
      global $order;

      $this->code = 'atm';
      $this->title = MODULE_PAYMENT_ATM_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_ATM_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_ATM_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_ATM_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_ATM_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_ATM_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    
      $this->email_footer = MODULE_PAYMENT_ATM_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order, $db;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_ATM_ZONE > 0) ) {
        $check_flag = false;
        $check_query = $db->Execute(
    "select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_ATM_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
      return array('title' => MODULE_PAYMENT_ATM_TEXT_DESCRIPTION1);
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
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ATM_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
	global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_ATM_STATUS', 'True', '6', '1', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_ATM_BANKCODE', '888','6', '1', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_ATM_BANKNAME', '臺灣銀行', '6', '1', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_ATM_ACCNUM', 'xx-xxx-xxx-xxx-xxx', '6', '1', now());");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_ATM_SORT_ORDER', '0', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_ATM_ZONE', '0', '6', '2', 'twe_get_zone_class_title', 'twe_cfg_pull_down_zone_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_ATM_ORDER_STATUS_ID', '0', '6', '0', 'twe_cfg_pull_down_order_statuses(', 'twe_get_order_status_name', now())");
  	  $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_ATM_ALLOWED', '',   '6', '0', now())");
    }

    function remove() {
	global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_ATM_STATUS', 'MODULE_PAYMENT_ATM_ALLOWED', 'MODULE_PAYMENT_ATM_BANKCODE', 'MODULE_PAYMENT_ATM_BANKNAME', 'MODULE_PAYMENT_ATM_ACCNUM', 'MODULE_PAYMENT_ATM_ZONE', 'MODULE_PAYMENT_ATM_ORDER_STATUS_ID', 'MODULE_PAYMENT_ATM_SORT_ORDER');
    }
  }
?>