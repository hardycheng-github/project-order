<?php
/* -----------------------------------------------------------------------------------------
   $Id: admin.php,v 1.4 2005/04/21 17:56:34 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (admin.php,v 1.12 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
if ($_SESSION['customers_status']['customers_status_id'] == 0){
// reset var
$box_smarty = new smarty;
$box_content='';
$flag='';
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
global $admin_link,$db;
  $orders_contents = '';
  $orders_status_query = "select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$_SESSION['languages_id'] . "'";
   $orders_status = $db->Execute(
    $orders_status_query);
 
  while (!$orders_status->EOF) {
    $orders_pending_query = "select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status->fields['orders_status_id'] . "'";
    $orders_pending = $db->Execute(
    $orders_pending_query);
    $orders_contents .= '<a href="' . twe_href_link_admin(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status->fields['orders_status_id'], 'SSL') . '">' . $orders_status->fields['orders_status_name'] . '</a>: ' . $orders_pending->fields['count'] . '<br>';
    $orders_status->MoveNext();
  }
  $orders_contents = substr($orders_contents, 0, -4);

  $customers_query = "select count(*) as count from " . TABLE_CUSTOMERS;
  $customers = $db->Execute($customers_query);
  $products_query = "select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'";
  $products = $db->Execute($products_query);
  $reviews_query = "select count(*) as count from " . TABLE_REVIEWS;
  $reviews = $db->Execute($reviews_query);
  $admin_image = '<a href="' . twe_href_link_admin(FILENAME_START,'', 'SSL').'">' . twe_image(DIR_WS_IMAGES.'admin_button.gif') .'</a>';
  if ($cPath != '' && $_GET['products_id'] != '') {
    $admin_link='<a href="' . twe_href_link_admin(FILENAME_EDIT_PRODUCTS, 'cPath=' . $cPath . '&pID=' . $_GET['products_id']) . '&action=new_product' . '" target="_blank">' . twe_image(DIR_WS_ICONS . 'edit_product.gif') . '</a>';
  }

  $box_content= '<b>' . BOX_TITLE_STATISTICS . '</b><br>' . $orders_contents . '<br>' .
                                         BOX_ENTRY_CUSTOMERS . ' ' . $customers->fields['count'] . '<br>' .
                                         BOX_ENTRY_PRODUCTS . ' ' . $products->fields['count'] . '<br>' .
                                         BOX_ENTRY_REVIEWS . ' ' . $reviews->fields['count'] .'<br>' .
                                         $admin_image . '<br>' .$admin_link;

    if ($flag==true) define('SEARCH_ENGINE_FRIENDLY_URLS',true);
    $box_smarty->assign('BOX_CONTENT', $box_content);

    $box_smarty->caching = 0;
    $box_smarty->assign('language', $_SESSION['language']);
    $box_admin= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_admin.html');
    $smarty->assign('admin',$box_admin);
	}
?>