<?php
/* -----------------------------------------------------------------------------------------
   $Id: address_book.php,v 1.5 2005/04/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(address_book.php,v 1.57 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (address_book.php,v 1.14 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
        // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_address_label.inc.php');
  require_once(DIR_FS_INC . 'twe_get_country_name.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_count_customer_address_book_entries.inc.php');
   require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }


  $breadcrumb->add(NAVBAR_TITLE_1_ADDRESS_BOOK, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ADDRESS_BOOK, twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'header.php');



  if ($messageStack->size('addressbook') > 0) {
  $smarty->assign('error',$messageStack->output('addressbook'));

  }
  $smarty->assign('ADDRESS_DEFAULT',twe_address_label($_SESSION['customer_id'], $_SESSION['customer_default_address_id'], true, ' ', '<br>'));

  $addresses_data=array();
  $addresses_query = "select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' order by firstname, lastname";
  $addresses =  $db->Execute($addresses_query);
  while (!$addresses->EOF) {
    $format_id = twe_get_address_format_id($addresses->fields['country_id']);
     if ($addresses->fields['address_book_id'] == $_SESSION['customer_default_address_id']) {
     $primary = 1;
     } else {
     $primary =0;
     }
    $addresses_data[]=array(
                          'NAME'=> $addresses->fields['firstname'] . ' ' . $addresses->fields['lastname'],
                          'BUTTON_EDIT'=> '<a href="' . twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses->fields['address_book_id'], 'SSL') . '">' . twe_image_button('small_edit.gif', SMALL_IMAGE_BUTTON_EDIT) . '</a>',
                          'BUTTON_DELETE'=> '<a href="' . twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses->fields['address_book_id'], 'SSL') . '">' . twe_image_button('small_delete.gif', SMALL_IMAGE_BUTTON_DELETE) . '</a>',
                          'ADDRESS'=> twe_address_format($format_id, $addresses->fields, true, ' ', '<br>'),
                          'PRIMARY'=> $primary);

$addresses->MoveNext();
  }
  $smarty->assign('addresses_data',$addresses_data);

  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');

  if (twe_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {


  $smarty->assign('BUTTON_NEW','<a href="' . twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">' . twe_image_button('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a>');
  }

  $smarty->assign('ADDRESS_COUNT',sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES));

  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/address_book.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>