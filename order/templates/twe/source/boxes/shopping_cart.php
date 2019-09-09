<?php
/* -----------------------------------------------------------------------------------------
   $Id: shopping_cart.php,v 1.3 2004/02/22 16:15:30 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.18 2003/02/10); www.oscommerce.com
   (c) 2003	 nextcommerce (shopping_cart.php,v 1.15 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
 if ($_SESSION['customers_status']['customers_status_show_price'] == 1) {
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
$box_price_string='';
$products_in_cart ='';
  // include needed files
  require_once(DIR_FS_INC . 'twe_format_price.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_separator.inc.php');
  require_once(DIR_FS_INC . 'twe_recalculate_price.inc.php');
  global $db;

 if (strstr($PHP_SELF, FILENAME_CHECKOUT_PAYMENT) or strstr($PHP_SELF, FILENAME_CHECKOUT_CONFIRMATION) or strstr($PHP_SELF, FILENAME_CHECKOUT_SHIPPING)) $box_smarty->assign('deny_cart','true');

  if ($_SESSION['cart']->count_contents() > 0) {
    $products = $_SESSION['cart']->get_products();
    $products_in_cart=array();
    $qty=0;
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
    $qty+=$products[$i]['quantity'];
      $products_in_cart[]=array(
                                'QTY'=>$products[$i]['quantity'],
                                'LINK'=>twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']),
                                'NAME'=>$products[$i]['name']);

    }
  $box_smarty->assign('PRODUCTS',$qty);
  $box_smarty->assign('empty','false');
  } else {
  // cart empty
  $box_smarty->assign('empty','true');
  }


  if ($_SESSION['cart']->count_contents() > 0) {
    $total_price =twe_format_price($_SESSION['cart']->show_total(), $price_special = 0, $calculate_currencies = false);
    if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00' ) {
      $box_smarty->assign('TOTAL',twe_format_price(($total_price), $price_special = 1, $calculate_currencies = false));
      $box_smarty->assign('DISCOUNT',twe_format_price(twe_recalculate_price(($total_price*(-1)), $_SESSION['customers_status']['customers_status_ot_discount']), $price_special = 1, $calculate_currencies = false));
    } else {
      $box_smarty->assign('TOTAL',twe_format_price(($total_price), $price_special = 1, $calculate_currencies = false));
    }

  }
  if (ACTIVATE_GIFT_SYSTEM=='true') {
  $box_smarty->assign('ACTIVATE_GIFT','true');
  }

     // GV Code Start
              if (isset($_SESSION['customer_id'])) {
                $gv_query = "select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'";
                $gv_result = $db->Execute($gv_query);
                if ($gv_result->fields['amount'] > 0 ) {
                    $box_smarty->assign('GV_AMOUNT', twe_format_price($gv_result->fields['amount'], $price_special = 1, $calculate_currencies = true));
                    $box_smarty->assign('GV_SEND_TO_FRIEND_LINK', '<a href="'. twe_href_link(FILENAME_GV_SEND) . '">');
                }
              }
              if (isset($_SESSION['gv_id'])) {
                $gv_query = "select coupon_amount, coupon_type from " . TABLE_COUPONS . " where coupon_id = '" . $_SESSION['gv_id'] . "'";
                $coupon = $db->Execute($gv_query);
				$coupon_amount2 = $coupon->fields['coupon_amount'];
              if($coupon->fields['coupon_type'] =='P'){
                $box_smarty->assign('COUPON_AMOUNT2', twe_format_price($coupon_amount2, $price_special = 0, $calculate_currencies = true).'%');
              }else{
                $box_smarty->assign('COUPON_AMOUNT2', twe_format_price($coupon_amount2, $price_special = 1, $calculate_currencies = true));
			  }
			  }
              if (isset($_SESSION['cc_id'])) {
                $box_smarty->assign('COUPON_HELP_LINK', '<a href="javascript:popupWindow(\'' . twe_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $_SESSION['cc_id']) . '\')">');
              }
    // GV Code End
    $box_smarty->assign('LINK_CART',twe_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
    $box_smarty->assign('products',$products_in_cart);

    $box_smarty->caching = 0;
    $box_smarty->assign('language', $_SESSION['language']);
    $box_shopping_cart= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_cart.html');
    $smarty->assign('shopping_cart',$box_shopping_cart);
	}
    ?>