<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_update_banner_display_count.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_update_banner_display_count.inc.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // Update the banner display statistics
  function twe_update_banner_display_count($banner_id) {
   global $db;
    $banner_check_query = "select count(*) as count from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')";
    $banner_check = $db->Execute($banner_check_query);

    if ($banner_check->fields['count'] > 0) {
    $db->Execute("update " . TABLE_BANNERS_HISTORY . " set banners_shown = banners_shown + 1 where banners_id = '" . $banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    } else {
    $db->Execute("insert into " . TABLE_BANNERS_HISTORY . " (banners_id, banners_shown, banners_history_date) values ('" . $banner_id . "', 1, now())");
    }
  }
?>