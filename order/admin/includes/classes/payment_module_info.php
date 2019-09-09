<?php
/* --------------------------------------------------------------
   $Id: payment_module_info.php,v 1.1 2003/09/06 22:05:29 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.twCopyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(payment_module_info.php,v 1.4 2002/11/22); www.oscommerce.com
   (c) 2003	 nextcommerce (payment_module_info.php,v 1.5 2003/08/18); www.nextcommerce.org 

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
   
  class paymentModuleInfo {
    var $payment_code, $keys;

    // class constructor
    function paymentModuleInfo($pmInfo_array) {
	global $db;
      $this->payment_code = $pmInfo_array['payment_code'];

      for ($i = 0, $n = sizeof($pmInfo_array) - 1; $i < $n; $i++) {
        $key_value_query = "select configuration_title, configuration_value, configuration_description from " . TABLE_CONFIGURATION . " where configuration_key = '" . $pmInfo_array[$i] . "'";
        $key_value = $db->Execute($key_value_query);

        $this->keys[$pmInfo_array[$i]]['title'] = $key_value->fields['configuration_title'];
        $this->keys[$pmInfo_array[$i]]['value'] = $key_value->fields['configuration_value'];
        $this->keys[$pmInfo_array[$i]]['description'] = $key_value->fields['configuration_description'];
      }
    }
  }
?>