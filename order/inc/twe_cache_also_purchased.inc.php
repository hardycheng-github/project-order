<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_cache_also_purchased.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cache.php,v 1.10 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_cache_also_purchased.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
//! Cache the also purchased module
// Cache the also purchased module
  function twe_cache_also_purchased($auto_expire = false, $refresh = false) {

    if (($refresh == true) || !read_cache($cache_output, 'also_purchased-' . $_SESSION['language'] . '.cache' . $_GET['products_id'], $auto_expire)) {
      ob_start();
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'also_purchased-' . $_SESSION['language'] . '.cache' . $_GET['products_id']);
    }

    return $cache_output;
  }
 ?>