<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_expire_banners.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_expire_banners.inc.php,v 1.5 2003/08/1); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require_once(DIR_FS_INC . 'twe_set_banner_status.inc.php');
   
// Auto expire banners
  function twe_expire_banners() {
    global $db;
    $banners_query = "select b.banners_id, b.expires_date, b.expires_impressions,
                             sum(bh.banners_shown) as banners_shown
                      from " . TABLE_BANNERS . " b, " . TABLE_BANNERS_HISTORY . " bh
                      where b.status = '1'
                      and b.banners_id = bh.banners_id
                      group by b.banners_id, b.expires_date, b.expires_impressions";

    $banners = $db->Execute($banners_query);

    if ($banners->RecordCount() > 0) {
      while (!$banners->EOF) {
        if (twe_not_null($banners->fields['expires_date'])) {
          if (date('Y-m-d H:i:s') >= $banners->fields['expires_date']) {
            twe_set_banner_status($banners->fields['banners_id'], '0');
          }
        } elseif (twe_not_null($banners->fields['expires_impressions'])) {
          if ( ($banners->fields['expires_impressions'] > 0) && ($banners->fields['banners_shown'] >= $banners->fields['expires_impressions']) ) {
            twe_set_banner_status($banners->fields['banners_id'], '0');
          }
        }
        $banners->MoveNext();
      }
    }
  }
 ?>