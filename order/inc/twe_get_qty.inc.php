<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_qty.inc.php,v 1.1 2003/11/07 20:27:01 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
      (c) 2003	 xt-commerce  www.xt-commerce.com

   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

   function twe_get_qty($products_id)  {

     if (strpos($products_id,'{'))  {
    $act_id=substr($products_id,0,strpos($products_id,'{'));
  } else {
    $act_id=$products_id;
  }

  return $_SESSION['actual_content'][$act_id]['qty'];

   }

?>