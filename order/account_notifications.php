<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_notifications.php,v 1.5 2004/02/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_notifications.php,v 1.2 2003/05/22); www.oscommerce.com
   (c) 2003	 nextcommerce (account_notifications.php,v 1.13 2003/08/17); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
        // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_selection_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }


  $global_query = "select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'";
  $global = $db->Execute($global_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['product_global']) && is_numeric($_POST['product_global'])) {
      $product_global = twe_db_prepare_input($_POST['product_global']);
    } else {
      $product_global = '0';
    }

    (array)$products = $_POST['products'];

    if ($product_global != $global->fields['global_product_notifications']) {
      $product_global = (($global->fields['global_product_notifications'] == '1') ? '0' : '1');
     $db->Execute("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '" . (int)$product_global . "' where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
     
	} elseif (sizeof($products) > 0) {
      $products_parsed = array();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        if (is_numeric($products[$i])) {
          $products_parsed[] = $products[$i];
        }
      }

      if (sizeof($products_parsed) > 0) {
        $check_query = "select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id not in (" . implode(',', $products_parsed) . ")";
        $check = $db->Execute($check_query);

        if ($check->fields['total'] > 0) {
        $db->Execute("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id not in (" . implode(',', $products_parsed) . ")");
  		}
      }
    } else {
      $check_query = "select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
      $check = $db->Execute($check_query);

      if ($check->fields['total'] > 0) {
       $db->Execute("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
 	  }
    }

    $messageStack->add_session('account', SUCCESS_NOTIFICATIONS_UPDATED, 'success');

    twe_redirect(twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1_ACCOUNT_NOTIFICATIONS, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ACCOUNT_NOTIFICATIONS, twe_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');



$smarty->assign('CHECKBOX_GLOBAL',twe_draw_checkbox_field('product_global', '1', (($global->fields['global_product_notifications'] == '1') ? true : false), 'onclick="checkBox(\'product_global\')"'));
if ($global->fields['global_product_notifications'] != '1') {
$smarty->assign('GLOBAL_NOTIFICATION','0');
} else {
$smarty->assign('GLOBAL_NOTIFICATION','1');
}
  if ($global->fields['global_product_notifications'] != '1') {

    $products_check_query = "select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
    $products_check = $db->Execute($products_check_query);
    if ($products_check->fields['total'] > 0) {

      $counter = 0;
      $notifications_products='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
      $products_query = "select pd.products_id, pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where pn.customers_id = '" . (int)$_SESSION['customer_id'] . "' and pn.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' order by pd.products_name";
      $products = $db->Execute($products_query);
	  while (!$products->EOF) {
      $notifications_products.= '

                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox(\'products->fields['.$counter.']\')">
                    <td class="main" width="30">'.twe_draw_checkbox_field('products->fields[' . $counter . ']', $products->fields['products_id'], true, 'onclick="checkBox(\'products->fields[' . $counter . ']\')"').'</td>
                    <td class="main"><b>'.$products->fields['products_name'].'</b></td>
                  </tr> ';

        $counter++;
	  $products->MoveNext();	
      }
      $notifications_products.= '</table>';
      $smarty->assign('PRODUCTS_NOTIFICATION',$notifications_products);
    } else {

    }

  }

  $smarty->assign('FORM_ACTION',twe_draw_form('account_notifications', twe_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL')) . twe_draw_hidden_field('action', 'process'));
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
  $smarty->assign('BUTTON_CONTINUE',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/account_notifications.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>