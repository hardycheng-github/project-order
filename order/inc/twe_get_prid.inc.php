<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_prid.inc.php,v 1.2 2004/03/01 19:16:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_get_prid.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Return a product ID from a product ID with attributes

  function twe_get_prid($uprid) {
  $pieces = explode('{', $uprid);

  if (is_numeric($pieces[0])) {
    return $pieces[0];
  } else {
    return false;
  }
}
 ?>