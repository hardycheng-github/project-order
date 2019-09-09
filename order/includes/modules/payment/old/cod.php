<?php
/* -----------------------------------------------------------------------------------------
   $Id: cod.php,v 1.1 2003/09/06 22:13:54 oldpa   Exp $   

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
   

  class cod {

    var $code, $title, $description, $enabled;


    function cod() {
      global $order;

      $this->code = 'cod';
      $this->title = MODULE_PAYMENT_COD_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_COD_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_COD_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_COD_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_COD_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_COD_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }


    function update_status() {
      global $order,$db;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_COD_ZONE > 0) ) {
        $check_flag = false;
        $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_COD_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while (!$check->EOF) {
          if ($check->fields['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
		$check->MoveNext();  		  
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }


      if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
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
      return false;
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
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_COD_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
	global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_COD_STATUS', 'True',  '6', '1', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  $db->Execute("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COD_ALLOWED', '', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_COD_ZONE', '0', '6', '2', 'twe_get_zone_class_title', 'twe_cfg_pull_down_zone_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_COD_SORT_ORDER', '0',  '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " ( configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_COD_ORDER_STATUS_ID', '0','6', '0', 'twe_cfg_pull_down_order_statuses(', 'twe_get_order_status_name', now())");
   }

    function remove() {
	global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_COD_STATUS','MODULE_PAYMENT_COD_ALLOWED', 'MODULE_PAYMENT_COD_ZONE', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', 'MODULE_PAYMENT_COD_SORT_ORDER');
    }
  }
?>