<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_reviews.php,v 1.5 2005/04/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_reviews.php,v 1.47 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_reviews.php,v 1.12 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
          // create smarty elements
  $smarty = new Smarty;
  // include boxes
 require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_row_number_format.inc.php');
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');

  // lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = twe_get_all_get_params();
  $get_params_back = twe_get_all_get_params(array('reviews_id')); // for back button
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if (twe_not_null($get_params_back)) {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }

  $product_info_query = "select pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id where pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.products_status = '1' and pd.products_id = '" . (int)$_GET['products_id'] . "'";
  $product_info = $db->Execute($product_info_query);

  if (!$product_info->RecordCount()) twe_redirect(twe_href_link(FILENAME_REVIEWS));


  $breadcrumb->add(NAVBAR_TITLE_PRODUCT_REVIEWS, twe_href_link(FILENAME_PRODUCT_REVIEWS, $get_params));

 require(DIR_WS_INCLUDES . 'header.php');

 $smarty->assign('PRODUCTS_NAME',$product_info->fields['products_name']);


$data_reviews=array();
  $reviews_query = "select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "' order by reviews_id DESC";
  $reviews = $db->Execute($reviews_query);
  if ($reviews->RecordCount()>0) {
    $row = 0;
    while (!$reviews->EOF) {
      $row++;
      $data_reviews[]=array(
                           'ID' => $reviews->fields['reviews_id'],
                           'AUTHOR'=> '<a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews->fields['reviews_id']) . '">' . $reviews->fields['customers_name'] . '</a>',
                           'DATE'=>twe_date_short($reviews->fields['date_added']),
                           'RATING'=>twe_image(DIR_WS_IMAGES . 'stars_' . $reviews->fields['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews->fields['reviews_rating'])),
                           'TEXT'=>$reviews->fields['reviews_text']);
   $reviews->MoveNext();
    }
  }
  $smarty->assign('module_content',$data_reviews);
  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_PRODUCT_INFO, $get_params_back) . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
  $smarty->assign('BUTTON_WRITE','<a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params) . '">' . twe_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>');


  $smarty->assign('language', $_SESSION['language']);


  // set cache ID
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/product_reviews.html');
  } else {
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_GET['products_id'];
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/product_reviews.html',$cache_id);
  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>