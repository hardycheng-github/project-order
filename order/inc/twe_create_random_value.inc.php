<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_create_random_value.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_create_random_value.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
      (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
// include needed functions
require_once(DIR_FS_INC . 'twe_rand.inc.php');
function twe_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = twe_rand(0,9);
      } else {
        $char = chr(twe_rand(0,255));
      }
      if ($type == 'mixed') {
        if (preg_match('/^[a-z0-9]$/', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (preg_match('/^[a-z]$/', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
//       if (preg_match('/^[0-9]$/', $char)) $rand_value .= $char;
        if (preg_match('/^[0-9]$/i', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }
 ?>
