<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_recalculate_price.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_recalculate_price.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function twe_recalculate_price($price, $discount) 
	{	  
	  $price=-100*$price/($discount-100)/100*$discount;
	  return $price;
      
	  }
 ?>