<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_activate_banners.inc.php,v 1.2 2005/01/07 20:27:01 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_activate_banners.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  require_once(DIR_FS_INC . 'twe_set_banner_status.inc.php'); 
// Auto activate banners
  function twe_activate_banners() {
    global $db;
    $banners_query = "select banners_id, date_scheduled
                      from " . TABLE_BANNERS . "
                      where date_scheduled != NULL";

    $banners = $db->Execute($banners_query);

    if ($banners->RecordCount() > 0) {
      while (!$banners->EOF) {
        if (date('Y-m-d H:i:s') >= $banners->fields['date_scheduled']) {
          twe_set_banner_status($banners->fields['banners_id'], '1');
        }
        $banners->MoveNext();
      }
    }
  }
 ?>