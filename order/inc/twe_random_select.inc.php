<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_random_select.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_random_select.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_random_select($query) {
    global $db;
    $random_product = '';
    $random_query = $db->Execute(
    $query);
    $num_rows = $random_query->RecordCount();
    if ($num_rows > 1) {
      $random_row = twe_rand(0, ($num_rows - 1));
      $random_query->Move($random_row);
    }
    return $random_query;
  }
 ?>