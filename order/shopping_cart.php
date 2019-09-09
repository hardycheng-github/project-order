<?php
/* -----------------------------------------------------------------------------------------
   $Id: shopping_cart.php,v 1.15 2005/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.71 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (shopping_cart.php,v 1.24 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
	if(!isset($shopping_cart_bottom)){
		$cart_empty=false;
		require("includes/application_top.php");
		require_once(DIR_FS_INC . 'icer_function.php');
		// create smarty elements
		$smarty = new Smarty;
		require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
		// include needed functions
		$breadcrumb->add(NAVBAR_TITLE_SHOPPING_CART, twe_href_link(FILENAME_SHOPPING_CART));

		require(DIR_WS_INCLUDES . 'header.php');
	}
  $smarty->assign('shopping_cart_bottom',isset($shopping_cart_bottom));
  include(DIR_WS_MODULES . 'gift_cart.php'); 
  require_once(DIR_FS_INC . 'twe_array_to_string.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_recalculate_price.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  

  
if ($_SESSION['cart']->count_contents() > 0) {
  $url = addGetVar($_SERVER['REQUEST_URI'], 'action', 'update_product');
  $smarty->assign('FORM_ACTION',$url);
  $hidden_options='';
  $_SESSION['any_out_of_stock']=0;

    $products = $_SESSION['cart']->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      // Push all attributes information in an array
      if (isset($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $hidden_options.= twe_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix,pa.attributes_stock,pa.products_attributes_id,pa.attributes_model,pa.weight_prefix,
		                             pa.options_values_weight
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . $products[$i]['id'] . "'
                                       and pa.options_id = '" . $option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . $value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                       and poval.language_id = '" . (int)$_SESSION['languages_id'] . "'";
          $attributes_values = $db->Execute($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values->fields['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values->fields['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values->fields['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values->fields['price_prefix'];
	      $products[$i][$option]['weight_prefix'] = $attributes_values->fields['weight_prefix'];
	      $products[$i][$option]['options_values_weight'] = $attributes_values->fields['options_values_weight'];
	      $products[$i][$option]['attributes_stock'] = $attributes_values->fields['attributes_stock'];
	      $products[$i][$option]['products_attributes_id'] = $attributes_values->fields['products_attributes_id'];
	      $products[$i][$option]['attributes_model'] = $attributes_values->fields['attributes_model'];
        }
      }
    }
	$smarty->assign('BUTTON_NEXT','<a href="'.$icer_cardNoQuery.'?deduct='.$_SESSION['cart']->show_total().'">'.twe_image_button('button_checkout.png', IMAGE_BUTTON_CHECKOUT).'</a>');
	$smarty->assign('HIDDEN_OPTIONS',$hidden_options);
    require(DIR_WS_MODULES. 'order_details_cart.php');

	if (STOCK_CHECK == 'true') {
		if ($_SESSION['any_out_of_stock']== 1) {
		  if (STOCK_ALLOW_CHECKOUT == 'true') {
			// write permission in session
			$_SESSION['allow_checkout'] = 'true';
			$smarty->assign('info_message',OUT_OF_STOCK_CAN_CHECKOUT);
		  } else {
			$_SESSION['allow_checkout'] = 'false';
			$smarty->assign('info_message',OUT_OF_STOCK_CANT_CHECKOUT);
		  }
		} else {
		  $_SESSION['allow_checkout'] = 'true';
		}
	}
	if ($_GET['info_message']) $smarty->assign('info_message',$_GET['info_message']);
	$url = twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=update_product');
	$smarty->assign('BUTTON_RELOAD',twe_image_submit('button_update_cart.png', IMAGE_BUTTON_UPDATE_CART));
} else {
  twe_redirect(twe_href_link(FILENAME_DEFAULT));
  // empty cart
  $cart_empty=true;
  $smarty->assign('cart_empty',$cart_empty);
  $smarty->assign('BUTTON_NEXT','<a href="'.twe_href_link(FILENAME_DEFAULT).'">'. twe_image_button('button_home.png')). '</a>';
}
if(!isset($_SESSION['invoice_no']) || strlen($_SESSION['invoice_no']) != 10){
	$smarty->assign('BUTTON_NEXT',twe_image_button('button_checkout.png', IMAGE_BUTTON_CHECKOUT, $button_next_disable_style));
}
$smarty->assign('ICON_ARROW','<img src="templates/'.CURRENT_TEMPLATE.'/img/icon_arrow.png">');
$smarty->assign('SELECT_LANG',twe_image('lang/' .  $_SESSION['language'] .'/select_lang.png', ''));

if ($_GET['info_message']) $smarty->assign('info_message',$_GET['info_message']);
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/shopping_cart.html');
  $smarty->assign('cart_content',$main_content);
  if(!isset($shopping_cart_bottom)){
	  $smarty->assign('main_content',$main_content);
	  $smarty->assign('shopping_cart_page',true);
	  $smarty->assign('language', $_SESSION['language']);
	  $smarty->caching = 0;
	  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  }
  unset($shopping_cart_bottom);
?>