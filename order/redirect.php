<?php
/* -----------------------------------------------------------------------------------------
   $Id: redirect.php,v 1.3 2004/01/02 00:08:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(redirect.php,v 1.9 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (redirect.php,v 1.7 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  require_once(DIR_FS_INC . 'twe_update_banner_click_count.inc.php');
  
  switch ($_GET['action']) {
    case 'banner':
	    global $db;

      $banner_query = "select banners_url from " . TABLE_BANNERS . " where banners_id = '" . $_GET['goto'] . "'";
      $banner = $db->Execute($banner_query);

      if ($banner->RecordCount() > 0) {
        twe_update_banner_click_count($_GET['goto']);

        twe_redirect($banner->fields['banners_url']);
      } else {
        twe_redirect(twe_href_link(FILENAME_DEFAULT));
      }
      break;

    case 'url':
      if (isset($_GET['goto'])) {
        twe_redirect('http://' . $_GET['goto']);
      } else {
        twe_redirect(twe_href_link(FILENAME_DEFAULT));
      }
      break;
   case 'groupurl':
      if (isset($_GET['goto'])) {
        twe_redirect($_GET['goto']);
      } else {
        twe_redirect(twe_href_link(FILENAME_DEFAULT));
      }
      break;

    case 'manufacturer':
	    global $db;

      if (isset($_GET['manufacturers_id'])) {
        $manufacturer = $db->Execute("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and languages_id = '" . (int)$_SESSION['languages_id'] . "'");
        if ($manufacturer->RecordCount()) {
          // no url exists for the selected language, lets use the default language then
          $manufacturer = $db->Execute("select mi.languages_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " mi, " . TABLE_LANGUAGES . " l where mi.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and mi.languages_id = l.languages_id and l.code = '" . DEFAULT_LANGUAGE . "'");
        if ($manufacturer->RecordCount()) {
            // no url exists, return to the site
            twe_redirect(twe_href_link(FILENAME_DEFAULT));
          } else {
            $manufacturer = $db->Execute($manufacturer_query);
           $db->Execute("update " . TABLE_MANUFACTURERS_INFO . " set url_clicked = url_clicked+1, date_last_click = now() where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and languages_id = '" . $manufacturer->fields['languages_id'] . "'");
            }
        } else {
          // url exists in selected language
          $manufacturer = $db->Execute($manufacturer_query);
         $db->Execute("update " . TABLE_MANUFACTURERS_INFO . " set url_clicked = url_clicked+1, date_last_click = now() where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and languages_id = '" . $_SESSION['languages_id'] . "'");
        }

        twe_redirect($manufacturer->fields['manufacturers_url']);
      } else {
        twe_redirect(twe_href_link(FILENAME_DEFAULT));
      }
      break;

    default:
      twe_redirect(twe_href_link(FILENAME_DEFAULT));
      break;
  }
?>