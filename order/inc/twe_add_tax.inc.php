<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_add_tax.inc.php,v 1.4 2004/02/29 12:08:27 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (twe_add_tax.inc.php,v 1.4 2003/08/24); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
   
function twe_add_tax($price, $tax) 
	{ 
	  $price=$price+$price/100*$tax;
	  return $price;
	  }
 ?>