<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_output_string.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_output_warning.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return @htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return twe_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return twe_parse_input_field_data($string, $translate);
      }
    }
  }
function twe_output_string_protected($string) {
    return twe_output_string($string, false, true);
  }

 ?>