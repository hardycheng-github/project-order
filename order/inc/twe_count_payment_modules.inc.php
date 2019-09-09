<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_count_payment_modules.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_count_payment_modules.inc.php,v 1.5 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  // include needed functions
  require_once(DIR_FS_INC . 'twe_count_modules.inc.php');
  function twe_count_payment_modules() {
    return twe_count_modules(MODULE_PAYMENT_INSTALLED);
  }
 ?>
