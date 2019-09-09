<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_reviews.php,v 1.6 2004/01/04 23:01:08 oldpa   Exp $   

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

  // create smarty elements
  $module_smarty = new Smarty;
  $module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  // include boxes
  // include needed functions
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_row_number_format.inc.php');
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');

     $info_smarty->assign('options',$products_options_data);
    $reviews_query = "select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "'";
    $reviews = $db->Execute($reviews_query);
    if ($reviews->fields['count'] > 0) {

      //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  $product_info_query = "select pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id where pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.products_status = '1' ".$fsk_lock." and pd.products_id = '" . (int)$_GET['products_id'] . "'";
  $product_info = $db->Execute($product_info_query);
 if ($product_info->RecordCount() < 0) 
   twe_redirect(twe_href_link(FILENAME_REVIEWS));


  $reviews_query = "select
                                 r.reviews_rating,
                                 r.reviews_id,
                                 r.customers_name,
                                 r.date_added,
                                 r.last_modified,
                                 r.reviews_read,
                                 rd.reviews_text
                                 from " . TABLE_REVIEWS . " r,
                                 ".TABLE_REVIEWS_DESCRIPTION ." rd
                                 where r.products_id = '" . (int)$_GET['products_id'] . "'
                                 and  r.reviews_id=rd.reviews_id
                                 and rd.languages_id = '".$_SESSION['languages_id']."'
                                 order by reviews_id DESC";
  $reviews = $db->Execute($reviews_query);
if ($reviews->RecordCount() > 0) {								 
    $row = 0;
    $data_reviews=array();
    while (!$reviews->EOF) {
      $row++;
      $data_reviews[]=array(
                           'AUTHOR'=>$reviews->fields['customers_name'],
                           'DATE'=>twe_date_short($reviews->fields['date_added']),
                           'RATING'=>twe_image(DIR_WS_IMAGES . 'stars_' . $reviews->fields['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews->fields['reviews_rating'])),
                           'TEXT' => htmlspecialchars($reviews->fields['reviews_text']) . ((strlen($reviews->fields['reviews_text']) >= 100) ? '..' : '')
);
	$reviews->MoveNext();					   
    if ($row==PRODUCT_REVIEWS_VIEW) break;
    }
  }

  $module_smarty->assign('BUTTON_WRITE','<a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . twe_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>');


  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$data_reviews);
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/products_reviews.html');
  $info_smarty->assign('MODULE_products_reviews',$module);
}
?>