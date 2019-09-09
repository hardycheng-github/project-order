<?php
/* -----------------------------------------------------------------------------------------
   $Id: reviews.php,v 1.6 2005/04/07 23:05:09 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(reviews.php,v 1.48 2003/05/27); www.oscommerce.com
   (c) 2003	 nextcommerce (reviews.php,v 1.12 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
      $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_word_count.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');


  $breadcrumb->add(NAVBAR_TITLE_REVIEWS, twe_href_link(FILENAME_REVIEWS));

 require(DIR_WS_INCLUDES . 'header.php');

  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 250) as reviews_text, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and rd.languages_id = '" . (int)$_SESSION['languages_id'] . "' order by r.reviews_id DESC";
  $reviews_split = new splitPageResults($reviews_query_raw, $_GET['page'], MAX_DISPLAY_NEW_REVIEWS);

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {

  $smarty->assign('NAVBAR','

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText">'. $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS).'</td>
            <td align="right" class="smallText">'.TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, twe_get_all_get_params(array('page', 'info', 'x', 'y'))).'</td>
          </tr>
        </table>
  ');

  }

$module_data=array();
  if ($reviews_split->number_of_rows > 0) {
    $reviews = $db->Execute($reviews_split->sql_query);
    while (!$reviews->EOF) {
    $module_data[]=array(
                         'PRODUCTS_IMAGE' =>DIR_WS_THUMBNAIL_IMAGES . $reviews->fields['products_image'], $reviews->fields['products_name'],
                         'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews->fields['products_id'] . '&reviews_id=' . $reviews->fields['reviews_id']),
                         'PRODUCTS_NAME' => $reviews->fields['products_name'],
                         'AUTHOR' => $reviews->fields['customers_name'],
                         'TEXT' => twe_break_string(twe_output_string_protected($reviews->fields['reviews_text']), 60, '-<br>') . ((strlen($reviews->fields['reviews_text']) >= 100) ? '..' : ''),
                         'RATING' => twe_image(DIR_WS_IMAGES . 'stars_' . $reviews->fields['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews->fields['reviews_rating'])));

    $reviews->MoveNext();
    }
    $smarty->assign('module_content',$module_data);
  }

  $smarty->assign('language', $_SESSION['language']);

  // set cache ID
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/reviews.html');
  } else {
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_GET['page'].$_SESSION['language'];
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/reviews.html',$cache_id);
  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>