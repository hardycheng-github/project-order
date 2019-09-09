<?php
/* -----------------------------------------------------------------------------------------
   $Id: also_purchased_products.php,v 1.11 2004/03/16 15:01:16 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(also_purchased_products.php,v 1.21 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (also_purchased_products.php,v 1.9 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  // include needed files
  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  require_once(DIR_FS_INC .  'twe_get_short_description.inc.php');

  if (isset($_GET['products_id'])) {

    //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $orders_query = "select p.products_fsk18, p.products_id, p.products_image from " .TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " .TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id ".$fsk_lock." and p.products_status = '1' ".$group_check." group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED;
    //$orders_query = $db->Execute("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $orders = $db->Execute($orders_query);
	$num_products_ordered = $orders->RecordCount();
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
      $row = 0;
      $module_content = array();
      while (!$orders->EOF) {
        $orders->fields['products_name'] = twe_get_products_name($orders->fields['products_id']);
        $orders->fields['products_short_description'] = twe_get_short_description($orders->fields['products_id']);

    if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
    $buy_now='';
    if ($_SESSION['customers_status']['customers_fsk18']=='1') {
        if ($orders->fields['products_fsk18']=='0') $buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $orders->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $orders->fields['products_name'] . TEXT_NOW);
    } else {
        $buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $orders->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $orders->fields['products_name'] . TEXT_NOW);
    }

	$module_content[]=array('PRODUCTS_ID' => $orders->fields['products_id'],
							'PRODUCTS_NAME' => $orders->fields['products_name'],
							'PRODUCTS_DESCRIPTION' => twe_get_short_description($orders->fields['products_id']),
							'PRODUCTS_PRICE' => twe_get_products_price($orders->fields['products_id'],$price_special=1,$quantity=1),
							'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders->fields['products_id']),
							'PRODUCTS_IMAGE' => DIR_WS_THUMBNAIL_IMAGES . $orders->fields['products_image'],
							'BUTTON_BUY_NOW'=>$buy_now);
  } else {
    $module_content[]=array('PRODUCTS_ID' => $orders->fields['products_id'],
                            'PRODUCTS_NAME' => $orders->fields['products_name'],
                            'PRODUCTS_DESCRIPTION' => twe_get_short_description($orders->fields['products_id']),
                            'PRODUCTS_PRICE' => twe_get_products_price($orders->fields['products_id'],$price_special=1,$quantity=1),
                            'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders->fields['products_id']),
                            'PRODUCTS_FSK18' => 'true',
                            'PRODUCTS_IMAGE' => DIR_WS_THUMBNAIL_IMAGES . $orders->fields['products_image']);

     }
    $row ++;
   $orders->MoveNext();	
   }

  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  // set cache ID
  if (USE_CACHE=='false') {
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/also_purchased.html');
  } else {
  $module_smarty->caching = 1;	
  $module_smarty->cache_lifetime=CACHE_LIFETIME;
  $module_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_GET['products_id'].$_SESSION['customers_status']['customers_status_name'];
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/also_purchased.html',$cache_id);
  }
  $info_smarty->assign('MODULE_also_purchased',$module);
  
    }
  }
?>