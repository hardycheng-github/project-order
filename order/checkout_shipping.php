<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_shipping.php,v 1.10 2004/02/17 21:13:26 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_shipping.php,v 1.15 2003/04/08); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_shipping.php,v 1.20 2003/08/20); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contribution:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org


   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
    // include needed functions

  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_address_label.inc.php');
  require_once(DIR_FS_INC . 'twe_get_address_format_id.inc.php');
  require_once(DIR_FS_INC . 'twe_count_shipping_modules.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_textarea_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_radio_field.inc.php');

  require(DIR_WS_CLASSES.'http_client.php');
  
  // check if checkout is allowed
  if ($_SESSION['allow_checkout']=='false') twe_redirect(twe_href_link(FILENAME_SHOPPING_CART));

  // if the customer is not logged on, redirect them to the login page
   if (!isset($_SESSION['customer_id'])) {
   twe_session_register('navigation');
   $_SESSION['navigation'] = new navigationHistory;
   $_SESSION['navigation']->set_snapshot();
   twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
 }

  // if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    twe_redirect(twe_href_link(FILENAME_SHOPPING_CART));
  }

  // if no shipping destination address was selected, use the customers own address as default
  if (!isset($_SESSION['sendto'])) {
    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
  } else {
    // verify the selected shipping address
    $check_address_query = "select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_SESSION['sendto'] . "'";
    $check_address = $db->Execute($check_address_query);

    if ($check_address->fields['total'] != '1') {
      $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
      if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  // register a random ID in the session to check throughout the checkout procedure
  // against alterations in the shopping cart contents
  $_SESSION['cartID'] = $_SESSION['cart']->cartID;

  // if the order contains only virtual products, forward the customer to the billing page as
  // a shipping address is not needed
if ($order->content_type == 'virtual' || ($order->content_type == 'virtual_weight') || ($_SESSION['cart']->count_contents_virtual() == 0)) { // GV Code added
    $_SESSION['shipping'] = false;
    $_SESSION['sendto'] = false;
    twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  $total_weight = $_SESSION['cart']->show_weight();
  //  $total_weight = $_SESSION['cart']['weight'];
  $total_count = $_SESSION['cart']->count_contents();

  if ($order->delivery['country']['iso_code_2'] != '') {
    $_SESSION['delivery_zone'] = $order->delivery['country']['iso_code_2'];
  }
  // load all enabled shipping modules
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
      case 'both':
        $pass = true; break;
      default:
        $pass = false; break;
    }

    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;

      include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
  
  if(isset($skip_checkout) && $skip_checkout == true){
	$_POST['action'] = 'process';
    $_POST['shipping'] = $shipping_value;
  }

  // process the selected shipping method
  if ( isset($_POST['action']) && ($_POST['action'] == 'process') ) {

    if ( (twe_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
        $_SESSION['shipping'] = $_POST['shipping'];

        list($module, $method) = explode('_', $_SESSION['shipping']);
        if ( is_object($$module) || ($_SESSION['shipping'] == 'free_free') ) {
          if ($_SESSION['shipping'] == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote['error'])) {
            unset($_SESSION['shipping']);
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $_SESSION['shipping'] = array('id' => $_SESSION['shipping'],
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);


              twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
            }
          }
        } else {
          unset($_SESSION['shipping']);
        }
      }
    } else {
      $_SESSION['shipping'] = false;
                
      twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    }    
  }

  // get all available shipping quotes
  $quotes = $shipping_modules->quote();

  // if no shipping method has been selected, automatically select the cheapest method.
  // if the modules status was changed when none were available, to save on implementing
  // a javascript force-selection method, also automatically select the cheapest shipping
  // method if more than one module is now enabled
  if ( !isset($_SESSION['shipping']) || ( isset($_SESSION['shipping']) && ($_SESSION['shipping'] == false) && (twe_count_shipping_modules() > 1) ) ) $_SESSION['shipping'] = $shipping_modules->cheapest();


  $breadcrumb->add(NAVBAR_TITLE_1_CHECKOUT_SHIPPING, twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_CHECKOUT_SHIPPING, twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php'); 

$smarty->assign('FORM_ACTION',twe_draw_form('checkout_address', twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . twe_draw_hidden_field('action', 'process'));
$smarty->assign('ADDRESS_LABEL',twe_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'));
$smarty->assign('BUTTON_ADDRESS','<a href="' . twe_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . twe_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>');
$smarty->assign('BUTON_CONTINUE',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));



  if (twe_count_shipping_modules() > 0) {

$shipping_block ='
<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">';


 
   if ($free_shipping == true) {

$shipping_block .='
              <tr>
                <td>'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td class="main" colspan="3"><b>'. FREE_SHIPPING_TITLE.'</b>&nbsp;'. $quotes[$i]['icon'].'</td>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>
                  <tr>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td class="main" width="100%">'. sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . twe_draw_hidden_field('shipping', 'free_free').'</td>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>
                </table></td>
                <td>'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td> 
              </tr>';

    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {

$shipping_block .='
              <tr>
                <td>'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td class="main" colspan="3"><b>'. $quotes[$i]['module'].'</b>&nbsp;'. $quotes[$i]['icon'].'</td>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>';

        if (isset($quotes[$i]['error'])) {
$shipping_block .='
                  <tr>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td class="main" colspan="3">'. $quotes[$i]['error'].'</td>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>';
        } else {
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
            // set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $_SESSION['shipping']['id']) ? true : false);

            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              $shipping_block .='                  <tr>' . "\n";
            } else {
              $shipping_block .= '                 <tr>' . "\n";
            }
$shipping_block .='
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td class="main" width="75%">'. $quotes[$i]['methods'][$j]['title'].'</td>
';
            if ( ($n > 1) || ($n2 > 1) ) {
              if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 ) $quotes[$i]['tax'] = '';
if ($_SESSION['customers_status']['customers_status_show_price_tax']==0)  $quotes[$i]['tax']=0;
              $shipping_block .='
                    <td class="main">'. twe_format_price(twe_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']),$price_special=1,$calculate_currencies=true).'</td>
                    <td class="main" align="right">'. twe_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked).'</td>
';
            } else {

if ($_SESSION['customers_status']['customers_status_show_price_tax']==0)  $quotes[$i]['tax']=0;
$shipping_block .='
                    <td class="main" align="right" colspan="2">'. twe_format_price(twe_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']),$price_special=1,$calculate_currencies=true) . twe_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']).'</td>
';
            }
$shipping_block .='
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>
';
            $radio_buttons++;
          }
        }
$shipping_block .='
                </table></td>
                <td>'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td> 
              </tr>
';
      }
    }
    
$shipping_block .='
            </table></td>
          </tr>
        </table>
';

  }
  




  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('SHIPPING_BLOCK',$shipping_block);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/checkout_shipping.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>