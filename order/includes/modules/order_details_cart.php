<?php
/* -----------------------------------------------------------------------------------------
   $Id: order_details_cart.php,v 1.11 2004/02/17 21:13:26 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(order_details.php,v 1.8 2003/05/03); www.oscommerce.com 
   (c) 2003	 nextcommerce (order_details.php,v 1.16 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contribution:

   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
$module_smarty=new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_separator.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_selection_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_check_stock.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_stock.inc.php');
  require_once(DIR_FS_INC . 'twe_remove_non_numeric.inc.php');
  require_once(DIR_FS_INC . 'twe_get_short_description.inc.php');

$module_content=array();
$any_out_of_stock='';
$mark_stock='';


  for ($i=0, $n=sizeof($products); $i<$n; $i++) {

  if (STOCK_CHECK == 'true') {
     $mark_stock= twe_check_stock($products[$i]['id'], $products[$i]['quantity']);
     if ($mark_stock) $_SESSION['any_out_of_stock']=1;
    }

  $image='';
  if ($products[$i]['image']!='') {
  $image=DIR_WS_THUMBNAIL_IMAGES.$products[$i]['image'];
  }
  $module_content[$i]=array(
			'PID' => $i,
  			'PRODUCTS_NAME' => $products[$i]['name'].$mark_stock,
  			'PRODUCTS_QTY' => twe_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'class="qtyCtl_text" ', 'text') . twe_draw_hidden_field('products_id[]', $products[$i]['id']),
  			'PRODUCTS_MODEL' => $products[$i]['model'],
            'PRODUCTS_TAX' => number_format($products[$i]['tax'], TAX_DECIMAL_PLACES),
  			'PRODUCTS_IMAGE' => $image,
  			'BOX_DELETE' => twe_draw_checkbox_field('cart_delete[]', $products[$i]['id']),
  			'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']),
  			'PRODUCTS_PRICE' => twe_get_products_price($products[$i]['id'], $price_special=1, $quantity=$products[$i]['quantity']),
            'PRODUCTS_TOTAL_PRICE'=>(int)twe_get_products_price($products[$i]['id'], $price_special=0, $quantity=$products[$i]['quantity']),
			'PRODUCTS_SINGLE_PRICE'=>twe_get_products_price($products[$i]['id'], $price_special=1, $quantity=1),
  			'PRODUCTS_SHORT_DESCRIPTION' => twe_get_short_description($products[$i]['id']),
  			'ATTRIBUTES' => '');



    // Product options names
    $attributes_exist = ((isset($products[$i]['attributes'])) ? 1 : 0);

    if ($attributes_exist == 1) {
      reset($products[$i]['attributes']);

      while (list($option, $value) = each($products[$i]['attributes'])) {

      	    if (ATTRIBUTE_STOCK_CHECK == 'true' && STOCK_CHECK == 'true') {
            $attribute_stock_check = twe_check_stock_attributes($products[$i][$option]['products_attributes_id'], $products[$i]['quantity']);
            if ($attribute_stock_check) $_SESSION['any_out_of_stock']=1;
          }
			$module_content[$i]['PRODUCTS_TOTAL_PRICE'] += (int)twe_get_products_attribute_price($products[$i][$option]['options_values_price'], $tax_class=$products[$i]['tax_class_id'],$price_special=0,$quantity=$products[$i]['quantity'],$prefix=$products[$i][$option]['price_prefix']);
			
      	$module_content[$i]['ATTRIBUTES'][]=array(
      				'ID' =>$products[$i][$option]['products_attributes_id'],
      				'MODEL'=>$products[$i][$option]['attributes_model'],
      				'NAME' => $products[$i][$option]['products_options_name'],
      				'VALUE_NAME' => $products[$i][$option]['products_options_values_name'].$attribute_stock_check,
					'SINGLE_PRICE' => twe_get_products_attribute_price($products[$i][$option]['options_values_price'], $tax_class=$products[$i]['tax_class_id'],$price_special=1,1,$prefix= $products[$i][$option]['price_prefix']),
      				'PRICE' => twe_get_products_attribute_price($products[$i][$option]['options_values_price'], $tax_class=$products[$i]['tax_class_id'],$price_special=1,$quantity=$products[$i]['quantity'],$prefix= $products[$i][$option]['price_prefix']));
      
	  }
	}
	$module_content[$i]['PRODUCTS_TOTAL_PRICE'] = twe_format_price($module_content[$i]['PRODUCTS_TOTAL_PRICE'],$price_special=1, $calculate_currencies=false);
  }

  /*$total_content='';
   if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00') {
      $discount = twe_recalculate_price($_SESSION['cart']->show_total(), $_SESSION['customers_status']['customers_status_ot_discount']);
    $total_content= TEXT_CART_OT_DISCOUNT.$_SESSION['customers_status']['customers_status_ot_discount'] . ' % ' . SUB_TITLE_OT_DISCOUNT . ' -' . twe_format_price($discount, $price_special=1, $calculate_currencies=false) .'<br>';
    }

    if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
      $total_content.= "<span class='cart_total'>".SUB_TITLE_TOTAL . twe_format_price($_SESSION['cart']->show_total(), $price_special=1, $calculate_currencies=false) . '</span><br>';
    } else {
     $total_content.= TEXT_INFO_SHOW_PRICE_NO . '<br>';
    }
    // display only if there is an ot_discount
    if ($customer_status_value->fields['customers_status_ot_discount'] != 0) {
      $total_content.= TEXT_CART_OT_DISCOUNT . $customer_status_value->fields['customers_status_ot_discount'] . '%';
    }*/
  
  $module_smarty->assign('image_btn_increase', twe_image_button('button_increase.png', IMAGE_BUTTON_INCREASE));
  $module_smarty->assign('image_btn_decrease', twe_image_button('button_decrease.png', IMAGE_BUTTON_DECREASE));
  $module_smarty->assign('image_btn_delete', twe_image_button('button_delete.png', IMAGE_BUTTON_DELETE));
  $module_smarty->assign('TOTAL_CONTENT',$total_content);
  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);

  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/order_details.html');

  $smarty->assign('MODULE_order_details',$module);
?>