<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_display_banner.inc.php,v 1.2 2004/02/28 19:16:49 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_display_banner.inc.php,v 1.3 2003/08/1); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Display a banner from the specified group or banner id ($identifier)
  function twe_display_banner($action, $identifier) {
   global $db;

    if ($action == 'dynamic') {
      $banners_query = "select count(*) as count from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'";
      $banners = $db->Execute($banners_query);
      if ($banners->fields['count'] > 0) {
        $banner = $db->Execute("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "' order by rand()");
      } else {
        return '<b>TWE ERROR! (twe_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</b>';
      }
    } elseif ($action == 'static') {
      if (is_object($identifier)) {
        $banner = $identifier;
      } else {
        $banner_query = "select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . $identifier . "'";
        
		$banner = $db->Execute($banner_query);

        if ($banner->RecordCount() >0) {
         return $banner;
        } else {
          return '<b>TWE ERROR! (twe_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</b>';
        }
      }
    } else {
      return '<b>TWE ERROR! (twe_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</b>';
    }

    if (twe_not_null($banner->fields['banners_html_text'])) {
      $banner_string = $banner->fields['banners_html_text'];
    } else {
    if (twe_not_null($banner->fields['banners_image'])) {
		//myEdit remove banner link
		$banner_string = twe_image(DIR_WS_IMAGES.'banner/' . $banner->fields['banners_image'], $banner->fields['banners_title']);
   
   //$banner_string = '<a href="' . twe_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner->fields['banners_id']) . '" target="_blank">' . twe_image(DIR_WS_IMAGES.'banner/' . $banner->fields['banners_image'], $banner->fields['banners_title']) . '</a>';
   }else{
   $banner_string ='';
   }
 }

    twe_update_banner_display_count($banner->fields['banners_id']);

    return $banner_string;
  }
 ?>