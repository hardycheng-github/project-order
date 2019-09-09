<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_reviews_info.php,v 1.8 2005/04/01 19:16:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_reviews_info.php,v 1.47 2003/02/13); www.oscommerce.com
   (c) 2003	 nextcommerce (product_reviews_info.php,v 1.12 2003/08/17); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
   // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php');  
  // include needed functions
  require_once(DIR_FS_INC . 'twe_break_string.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  // lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = twe_get_all_get_params(array('reviews_id'));
  $get_params = substr($get_params, 0, -1); //remove trailing &

  $reviews_query = "select rd.reviews_text, r.reviews_rating, r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, p.products_id, pd.products_name, p.products_image from " . TABLE_REVIEWS . " r left join " . TABLE_REVIEWS_DESCRIPTION . " rd on (r.reviews_id = rd.reviews_id) left join " . TABLE_PRODUCTS . " p on (r.products_id = p.products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id and pd.language_id = '". (int)$_SESSION['languages_id'] . "') where r.reviews_id = '" . (int)$_GET['reviews_id'] . "'  and p.products_status = '1'";
  $reviews = $db->Execute($reviews_query);

  if (!$reviews->RecordCount()) twe_redirect(twe_href_link(FILENAME_REVIEWS));


  $breadcrumb->add(NAVBAR_TITLE_PRODUCT_REVIEWS, twe_href_link(FILENAME_PRODUCT_REVIEWS, $get_params));

  $db->Execute("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . $reviews->fields['reviews_id'] . "'");

  $reviews_text = htmlspecialchars($reviews->fields['reviews_text']);


 require(DIR_WS_INCLUDES . 'header.php');

 $smarty->assign('PRODUCTS_NAME',$reviews->fields['products_name']);
 $smarty->assign('AUTHOR',$reviews->fields['customers_name']);
 $smarty->assign('DATE',twe_date_long($reviews->fields['date_added']));
 $smarty->assign('REVIEWS_TEXT',nl2br($reviews_text));
 $smarty->assign('RATING',twe_image(DIR_WS_IMAGES . 'stars_' . $reviews->fields['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews->fields['reviews_rating'])));
 $smarty->assign('PRODUCTS_LINK',twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $reviews->fields['products_id']));
 $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS, $get_params) . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
 $smarty->assign('BUTTON_BUY_NOW','<a href="' . twe_href_link(FILENAME_DEFAULT, 'action=buy_now&BUYproducts_id=' . $reviews->fields['products_id']) . '">' . twe_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART));
 $smarty->assign('IMAGE','<a href="javascript:popupImageWindow(\''. twe_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $reviews->fields['products_id']).'\')">'. twe_image(DIR_WS_THUMBNAIL_IMAGES . $reviews->fields['products_image'], $reviews->fields['products_name'], '', '', 'align="center" hspace="5" vspace="5"').'<br></a>');

  $smarty->assign('language', $_SESSION['language']);


  // set cache ID
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/product_reviews_info.html');
  } else {
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$reviews->fields['reviews_id'];
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/product_reviews_info.html',$cache_id);
  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>