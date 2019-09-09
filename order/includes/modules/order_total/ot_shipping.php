<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_shipping.php,v 1.2 2004/01/07 14:08:56 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_shipping.php,v 1.15 2003/02/07); www.oscommerce.com 
   (c) 2003	 nextcommerce (ot_shipping.php,v 1.13 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  class ot_shipping {
    var $title, $output;

    function ot_shipping() {
      $this->code = 'ot_shipping';
      $this->title = MODULE_ORDER_TOTAL_SHIPPING_TITLE;
      $this->description = MODULE_ORDER_TOTAL_SHIPPING_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_SHIPPING_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;

      if (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') {
        switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
          case 'national':
            if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
          case 'international':
            if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
          case 'both':
            $pass = true; break;
          default:
            $pass = false; break;
        }

        if ( ($pass == true) && ( ($order->info['total'] - $order->info['shipping_cost']) >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
          $order->info['shipping_method'] = $this->title;
          $order->info['total'] -= $order->info['shipping_cost'];
          $order->info['shipping_cost'] = 0;
        }
      }

      $module = substr($_SESSION['shipping']['id'], 0, strpos($_SESSION['shipping']['id'], '_'));

      if (twe_not_null($order->info['shipping_method'])) {
        if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 1) {
        // price with tax

        $order->info['shipping_cost']=twe_format_price($order->info['shipping_cost'], $price_special=0, $calculate_currencies=true);

          $shipping_tax = twe_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $shipping_tax_description = twe_get_tax_description($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax = twe_add_tax($order->info['shipping_cost'], $shipping_tax)-$order->info['shipping_cost'];
          $order->info['shipping_cost'] = twe_add_tax($order->info['shipping_cost'], $shipping_tax);

          $order->info['tax'] += $tax;
          $order->info['tax_groups'][TAX_ADD_TAX . "$shipping_tax_description"] += $tax;
        $order->info['total'] += $tax;

        } else {

        if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
        $order->info['shipping_cost']=twe_format_price($order->info['shipping_cost'], $price_special=0, $calculate_currencies=true);
          $shipping_tax = twe_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $shipping_tax_description = twe_get_tax_description($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax = twe_add_tax($order->info['shipping_cost'], $shipping_tax)-$order->info['shipping_cost'];


          $order->info['tax'] = $order->info['tax'] += $tax;
          $order->info['tax_groups'][TAX_NO_TAX . "$shipping_tax_description"] = $order->info['tax_groups'][TAX_NO_TAX . "$shipping_tax_description"] += $tax;
        } else {
        $order->info['shipping_cost']=twe_format_price($order->info['shipping_cost'], $price_special=0, $calculate_currencies=true);
        }
        }
        $this->output[] = array('title' => $order->info['shipping_method'] . ':',
                                'text' => twe_format_price($order->info['shipping_cost'], $price_special=1, $calculate_currencies=false),
                                'value' => twe_format_price($order->info['shipping_cost'], $price_special=0, $calculate_currencies=false));
      }
    }

    function check() {
	global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_SHIPPING_STATUS'");
        $this->_check = $check_query->RecordCount();
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION');
    }

    function install() {
	global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true','6', '1','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '3','6', '2', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false','6', '3', 'twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) values ('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', '6', '4', 'currencies->format', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national','6', '5', 'twe_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now())");
    }

    function remove() {
	global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>