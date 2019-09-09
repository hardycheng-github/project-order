<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_subtotal.php,v 1.3 2004/02/21 22:38:22 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_subtotal.php,v 1.7 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (ot_subtotal.php,v 1.10 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  class ot_subtotal {
    var $title, $output;

    function ot_subtotal() {
      $this->code = 'ot_subtotal';
      $this->title = MODULE_ORDER_TOTAL_SUBTOTAL_TITLE;
      $this->description = MODULE_ORDER_TOTAL_SUBTOTAL_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_SUBTOTAL_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;
      if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 0) {
        $this->output[] = array('title' => MODULE_ORDER_TOTAL_SUBTOTAL_TITLE_NO_TAX . ':',
                                'text' => twe_format_price($order->info['subtotal'],1,false),
                                'value' => $order->info['subtotal']);
      }
      if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
        $this->output[] = array('title' => $this->title . ':',
                                'text' => twe_format_price($order->info['subtotal'],1,false),
                                'value' => $order->info['subtotal']);
      } 
      if ($_SESSION['customers_status']['customers_status_show_price_tax'] != 0) {
        $this->output[] = array('title' => $this->title . ':',
                                'text' => twe_format_price($order->info['subtotal'],1,false),
                                'value' => $order->info['subtotal']);
      }


    }

    function check() {
	global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS'");
        $this->_check = $check_query->RecordCount();
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER');
    }

    function install() {
	global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', '6', '1','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', '6', '2', now())");
    }

    function remove() {
	global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>