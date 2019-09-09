<?php
/* -----------------------------------------------------------------------------------------
   $Id: order_history.php,v 1.1 2005/04/07 23:02:54 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(order_history.php,v 1.4 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (order_history.php,v 1.9 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
if (isset($_SESSION['customer_id'])){
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
  
  $group_check=''; 
 if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }

$customer_orders_string ='';
  if (isset($_SESSION['customer_id'])) {
    // retreive the last x products purchased
    $orders_query = "select distinct op.products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$_SESSION['customer_id'] . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = 1 ".$group_check." group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX;
    $orders = $db->Execute($orders_query);
	 if ($orders->RecordCount()>0){
      $product_ids = '';
      while (!$orders->EOF) {
        $product_ids .= $orders->fields['products_id'] . ',';
	   $orders->MoveNext();
      }
      $product_ids = substr($product_ids, 0, -1);

      $customer_orders_string = '<table border="0" width="100%" cellspacing="0" cellpadding="1">';
      $products_query = "select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $product_ids . ") and language_id = '" . (int)$_SESSION['languages_id'] . "' order by products_name";
      $products = $db->Execute($products_query);
	  while (!$products->EOF) {
        $customer_orders_string .= '  <tr>' .
                                   '    <td class="infoBoxContents"><a href="' . twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products->fields['products_id']) . '">' . $products->fields['products_name'] . '</a></td>' .
                                   '    <td class="infoBoxContents" align="right" valign="top"><a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $products->fields['products_id']) . '">' . twe_image(DIR_WS_ICONS . 'cart.gif', ICON_CART) . '</a></td>' .
                                   '  </tr>';
		$products->MoveNext();					   
      }
      $customer_orders_string .= '</table>';
    }
  }
  $box_smarty->assign('language', $_SESSION['language']);
  if(twe_not_null($customer_orders_string)){
    $box_smarty->assign('BOX_CONTENT', $customer_orders_string);
    $box_smarty->caching = 0;
    $box_smarty->assign('language', $_SESSION['language']);
    $box_order_history= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_order_history.html');
    $smarty->assign('order_history',$box_order_history);  
	}
	}  
  ?>