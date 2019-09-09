<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_banner_exists.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_banner_exists.inc.php,v 1.4 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  function twe_banner_exists($action, $identifier) {
  global $db;  
    if ($action == 'dynamic') {
      return $db->Execute("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "' order by rand()");
    } elseif ($action == 'static') {
      $banner_query = "select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . $identifier . "'";
      return $banner = $db->Execute($banner_query);
    } else {
      return false;
    }
  }
 ?>