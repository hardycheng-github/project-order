<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_precision.inc.php,v 1.2 2003/11/10 20:42:36 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_precision.inc.php,v 1.5 2003/08/19); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function twe_precision($number,$places)
	{
	 return (round($number,$places));
	}
 ?>