<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_set_specials_status.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.5 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_set_specials_status.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Sets the status of a special product
  function twe_set_specials_status($specials_id, $status) {
  global $db;
  $sql = "update " . TABLE_SPECIALS . " set status = '" . $status . "', date_status_change = now() where specials_id = '" . $specials_id . "'";
  $db->Execute(
    $sql);
  }
 ?>