<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_prepare_country_zones_pull_down.inc.php, 2005/04/21 17:55:00 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_href_link.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   require_once(DIR_FS_INC . 'twe_array_merge.inc.php');
   require_once(DIR_FS_INC . 'twe_browser_detect.inc.php');
   require_once(DIR_FS_INC . 'twe_get_country_zones.inc.php');
 function twe_prepare_country_zones_pull_down($country_id = '') {
    // preset the width of the drop-down for Netscape
    $pre = '';
    if ( (!twe_browser_detect('MSIE')) && (twe_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
    }

    $zones = twe_get_country_zones($country_id);

    if (sizeof($zones) > 0) {
      $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
      $zones = twe_array_merge($zones_select, $zones);
    } else {
      $zones = array(array('id' => '', 'text' => TYPE_BELOW));
      // create dummy options for Netscape to preset the height of the drop-down
      if ( (!twe_browser_detect('MSIE')) && (twe_browser_detect('Mozilla/4')) ) {
        for ($i=0; $i<9; $i++) {
          $zones[] = array('id' => '', 'text' => $pre);
        }
      }
    }

    return $zones;
  }
?>