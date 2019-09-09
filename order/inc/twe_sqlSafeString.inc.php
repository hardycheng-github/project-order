<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_sqlSafeString.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for neXTCommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_sqlSafeString.inc.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  function twe_sqlSafeString($param) {
    // Hier wird wg. der grossen Verbreitung auf MySQL eingegangen
    return (NULL === $param ? "NULL" : '"' . mysql_escape_string($param) . '"');
  }
?>