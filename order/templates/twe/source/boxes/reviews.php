<?php
/* -----------------------------------------------------------------------------------------
   $Id: reviews.php,v 1.1 2004/02/07 23:02:54 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(reviews.php,v 1.36 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (reviews.php,v 1.9 2003/08/17 22:40:08); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
  // include needed functions
  require_once(DIR_FS_INC . 'twe_random_select.inc.php');
  require_once(DIR_FS_INC . 'twe_break_string.inc.php');
  require_once(DIR_FS_INC . 'twe_output_string.inc.php');
 
 $group_check=''; 
 if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
      //fsk18 lock
  $fsk_lock="";
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=" and p.products_fsk18!='1'";
  }
  $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status =1 ".$group_check." and p.products_id = r.products_id ".$fsk_lock." and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$_SESSION['languages_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
  if (isset($_GET['products_id'])) {
    $random_select .= " and p.products_id = '" . (int)$_GET['products_id'] . "'";
  }
  $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
  $random_product = twe_random_select($random_select);


  if ($random_product->RecordCount() > 0) {
    // display random review box
    $review_query = "select substring(reviews_text, 1, 60) as reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $random_product->fields['reviews_id'] . "' and languages_id = '" . $_SESSION['languages_id'] . "'";
    $review = $db->Execute($review_query);
    //$review = twe_break_string(twe_output_string_protected($review->fields['reviews_text']), 17, '');

    $review = htmlspecialchars($review->fields['reviews_text']);
    //$review = twe_break_string($review, 15, '-<br>');

    $box_content = '<div align="center"><a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product->fields['products_id'] . '&reviews_id=' . $random_product->fields['reviews_id']) . '">' . twe_image(DIR_WS_THUMBNAIL_IMAGES . $random_product->fields['products_image'], $random_product->fields['products_name']) . '</a></div><a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product->fields['products_id'] . '&reviews_id=' . $random_product->fields['reviews_id']) . '">' . $review . ' ..</a><br><div align="center">' . twe_image(DIR_WS_IMAGES . 'stars_' . $random_product->fields['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product->fields['reviews_rating'])) . '</div>';


  } elseif (isset($_GET['products_id'])) {
    // display 'write a review' box
    $box_content = '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . twe_image(DIR_WS_IMAGES . 'box_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a></td><td class="infoBoxContents"><a href="' . twe_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></td></tr></table>';
   }

  if ($box_content!='') {
  $box_smarty->assign('REVIEWS_LINK',twe_href_link(FILENAME_REVIEWS)); 
  $box_smarty->assign('BOX_CONTENT', $box_content);
  $box_smarty->assign('language', $_SESSION['language']);
  // set cache ID
  if (USE_CACHE=='false') {
  $box_smarty->caching = 0;
  $box_reviews= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_reviews.html');
  } else {
  $box_smarty->caching = 1;
  $box_smarty->cache_lifetime=CACHE_LIFETIME;
  $box_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$random_product->fields['reviews_id'];
  $box_reviews= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_reviews.html',$cache_id);
  }
  $smarty->assign('reviews',$box_reviews);

  } 

?>