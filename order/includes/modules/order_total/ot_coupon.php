<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_coupon.php,v 1.1 2004/02/17 21:13:27 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_coupon.php,v 1.1.2.37.3); www.oscommerce.com
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


class ot_coupon {
var $title, $output;

function ot_coupon() {

	$this->code = 'ot_coupon';
	$this->header = MODULE_ORDER_TOTAL_COUPON_HEADER;
	$this->title = MODULE_ORDER_TOTAL_COUPON_TITLE;
	$this->description = MODULE_ORDER_TOTAL_COUPON_DESCRIPTION;
	$this->user_prompt = '';
	$this->enabled = MODULE_ORDER_TOTAL_COUPON_STATUS;
	$this->sort_order = MODULE_ORDER_TOTAL_COUPON_SORT_ORDER;
	$this->include_shipping = MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING;
	$this->include_tax = MODULE_ORDER_TOTAL_COUPON_INC_TAX;
	$this->calculate_tax = MODULE_ORDER_TOTAL_COUPON_CALC_TAX;
	$this->tax_class = MODULE_ORDER_TOTAL_COUPON_TAX_CLASS;
	$this->credit_class = true;
	$this->output = array();

}

function process() {
global $order, $currencies;


	$order_total=$this->get_order_total();
	$od_amount = $this->calculate_credit($order_total);
	$tod_amount = 0.0; //Fred
    $this->deduction = twe_format_price($od_amount, $price_special=0, $calculate_currencies=true);
	if ($this->calculate_tax != 'None') { //Fred - changed from 'none' to 'None'!
		$tod_amount = $this->calculate_tax_deduction($order_total, $this->deduction, $this->calculate_tax);
	}

	if ($od_amount > 0) {
		$order->info['total'] = $order->info['total'] - $this->deduction;
		$this->output[] = array('title' => $this->title . ':' . $this->coupon_code .':','text' => '<b>-' . $currencies->format($od_amount) . '</b>', 'value' => $od_amount); //Fred added hyphen
	}
}

function selection_test() {
	return false;
}


function pre_confirmation_check($order_total) {

	return $this->calculate_credit($order_total);
}

function use_credit_amount() {
	return $output_string;
}


function credit_selection() {
global $currencies;

	/*$selection_string = '';
	$selection_string .= '<tr>' . "\n";
	$selection_string .= ' <td  width="10">' . twe_draw_separator('pixel_trans.gif', '10', '1') .'</td>';
	$selection_string .= ' <td  nowrap class="main">' . "\n";
	$selection_string .=  TEXT_ENTER_COUPON_CODE . '</td>';
	$selection_string .= ' <td  align="right">'. twe_draw_input_field('gv_redeem_code').'</td>';
	$selection_string .= ' <td  width="10">' . twe_draw_separator('pixel_trans.gif', '10', '1') . '</td>';
	$selection_string .= '</tr>' . "\n";
    */
	return $selection_string;
}


function collect_posts() {
global $currencies, $db;
	if ($_POST['gv_redeem_code']) {

// get some info from the coupon table
	$coupon_query="select coupon_id, coupon_amount, coupon_type, coupon_minimum_order,uses_per_coupon, uses_per_user, restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='".$_POST['gv_redeem_code']."' and coupon_active='Y'";
	$coupon_result=$db->Execute($coupon_query);

// SS ?
	if ($coupon_result->fields['coupon_type'] != 'G') {
 
 if ($coupon_result->RecordCount()==0) {
		twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_INVALID_REDEEM_COUPON), 'SSL'));
		}

		$date_query=$db->Execute("select coupon_start_date from " . TABLE_COUPONS . " where coupon_start_date <= now() and coupon_code='".$_POST['gv_redeem_code']."'");
 if ($date_query->RecordCount()==0) {
			twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_STARTDATE_COUPON), 'SSL'));
		}
		
		$coupon_count = $db->Execute("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result->fields['coupon_id']."'");
		
		$coupon_count_customer = $db->Execute("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result->fields['coupon_id']."' and customer_id = '" . $_SESSION['customer_id'] . "'");

		if ($coupon_count->RecordCount()>=$coupon_result->fields['uses_per_coupon'] && $coupon_result->fields['uses_per_coupon'] > 0) {
			twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_USES_COUPON . $coupon_result->fields['uses_per_coupon'] . TIMES ), 'SSL'));
		}

		if ($coupon_count_customer->RecordCount()>=$coupon_result->fields['uses_per_user'] && $coupon_result->fields['uses_per_user'] > 0) {
			twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_INVALID_USES_USER_COUPON . $coupon_result->fields['uses_per_user'] . TIMES ), 'SSL'));
		}
		if ($coupon_result->fields['coupon_type']=='S') {
			$coupon_amount = $order->info['shipping_cost'];
		} else {
			$coupon_amount = $currencies->format($coupon_result->fields['coupon_amount']) . ' ';
		}
		if ($coupon_result->fields['coupon_type']=='P') $coupon_amount = $coupon_result->fields['coupon_amount'] . '% ';
		if ($coupon_result->fields['coupon_minimum_order']>0) $coupon_amount .= 'on orders greater than ' . $coupon_result->fields['coupon_minimum_order'];
		
		$_SESSION['cc_id'] = $coupon_result->fields['coupon_id']; //Fred ADDED, set the global and session variable
		
	}
	if ($_POST['submit_redeem_coupon_x'] && !$_POST['gv_redeem_code']) twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_REDEEM_CODE), 'SSL'));
	}
}

function calculate_credit($amount) {
global $order, $db;

	$od_amount = 0;
	if (isset($_SESSION['cc_id']) ) {
		$coupon_result = $db->Execute("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . $_SESSION['cc_id'] . "'");
		if ($coupon_result->RecordCount() !=0 ) {
			$this->coupon_code = $coupon_result->fields['coupon_code'];
			$coupon_get = "select coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type from " . TABLE_COUPONS ." where coupon_code = '". $coupon_result->fields['coupon_code'] . "'";
			$get_result = $db->Execute($coupon_get);
			$c_deduct = $get_result->fields['coupon_amount'];
			if ($get_result->fields['coupon_type']=='S') $c_deduct = $order->info['shipping_cost'];
			if ($get_result->fields['coupon_minimum_order'] <= $this->get_order_total()) {
				if ($get_result->fields['restrict_to_products'] || $get_result->fields['restrict_to_categories']) {
					for ($i=0; $i<sizeof($order->products); $i++) {
						if ($get_result->fields['restrict_to_products']) {
							$pr_ids = preg_split("[,]", $get_result->fields['restrict_to_products']);
							for ($ii = 0; $ii < count($pr_ids); $ii++) {
								if ($pr_ids[$ii] == twe_get_prid($order->products[$i]['id'])) {
									if ($get_result->fields['coupon_type'] == 'P') {
											/* Fixes to Gift Voucher module 5.03
											=================================
											Submitted by Rob Cote, robc@traininghott.com

											original code: $od_amount = round($amount*10)/10*$c_deduct/100;
											$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
											$pod_amount = round($pr_c*10)/10*$c_deduct/100;
											*/
											//$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
											$pr_c = $this->product_price($pr_ids[$ii]); //Fred 2003-10-28, fix for the row above, otherwise the discount is calc based on price excl VAT!
											$pod_amount = round($pr_c*10)/10*$c_deduct/100;
											$od_amount = $od_amount + $pod_amount;
										} else {
											$od_amount = $c_deduct;
										}
									}
								}
							} else {
								$cat_ids = preg_split("[,]", $get_result->fields['restrict_to_categories']);
								for ($i=0; $i<sizeof($order->products); $i++) {
									$my_path = twe_get_product_path(twe_get_prid($order->products[$i]['id']));
									$sub_cat_ids = preg_split("[_]", $my_path);
									for ($iii = 0; $iii < count($sub_cat_ids); $iii++) {
										for ($ii = 0; $ii < count($cat_ids); $ii++) {
											if ($sub_cat_ids[$iii] == $cat_ids[$ii]) {
												if ($get_result->fields['coupon_type'] == 'P') {
													/* Category Restriction Fix to Gift Voucher module 5.04
													Date: August 3, 2003
													=================================
													Nick Stanko of UkiDev.com, nick@ukidev.com

													original code:
													$od_amount = round($amount*10)/10*$c_deduct/100;
													$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
													$pod_amount = round($pr_c*10)/10*$c_deduct/100;
													*/
													//$od_amount = round($amount*10)/10*$c_deduct/100;
													//$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
													$pr_c = $this->product_price(twe_get_prid($order->products[$i]['id'])); //Fred 2003-10-28, fix for the row above, otherwise the discount is calc based on price excl VAT!
													$pod_amount = round($pr_c*10)/10*$c_deduct/100;
													$od_amount = $od_amount + $pod_amount;
												} else {
													$od_amount = $c_deduct;
												}
											}
										}
									}
								}
							}
						}
					} else {
						if ($get_result->fields['coupon_type'] !='P') {
							$od_amount = $c_deduct;
						} else {
							$od_amount = $amount * $get_result->fields['coupon_amount'] / 100;
						}
					}
				}
			}
		if ($od_amount>$amount) $od_amount = $amount;
		}
	return $od_amount;
}

function calculate_tax_deduction($amount, $od_amount, $method) {
global $order, $db;

	$coupon_result = $db->Execute("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . $_SESSION['cc_id'] . "'");
	if ($coupon_result->RecordCount() !=0 ) {
		$coupon_get = "select coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type from " . TABLE_COUPONS . " where coupon_code = '". $coupon_result->fields['coupon_code'] . "'";
		$get_result = $db->Execute($coupon_get);
		if ($get_result->fields['coupon_type'] != 'S') {

			//RESTRICTION--------------------------------
			if ($get_result->fields['restrict_to_products'] || $get_result->fields['restrict_to_categories']) {
				// What to do here.
				// Loop through all products and build a list of all product_ids, price, tax class
				// at the same time create total net amount.
				// then
				// for percentage discounts. simply reduce tax group per product by discount percentage
				// or
				// for fixed payment amount
				// calculate ratio based on total net
				// for each product reduce tax group per product by ratio amount.
				$products = $_SESSION['cart']->get_products();
				$valid_product = false;
				for ($i=0; $i<sizeof($products); $i++) {
				$valid_product = false;
					$t_prid = twe_get_prid($products[$i]['id']);
					$cc_query = "select products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '" . $t_prid . "'";
					$cc_result = $db->Execute($cc_query);
					if ($get_result->fields['restrict_to_products']) {
						$pr_ids = preg_split("[,]", $get_result->fields['restrict_to_products']);
						for ($p = 0; $p < sizeof($pr_ids); $p++) {
							if ($pr_ids[$p] == $t_prid) $valid_product = true;
						}
					}
					if ($get_result->fields['restrict_to_categories']) {
						$cat_ids = preg_split("[,]", $get_result->fields['restrict_to_categories']);
						for ($c = 0; $c < sizeof($cat_ids); $c++) {
							$cat_query = $db->Execute("select products_id from products_to_categories where products_id = '" . $products_id . "' and categories_id = '" . $cat_ids[$i] . "'");
							if ($cat_query->RecordCount() !=0 ) $valid_product = true;
						}
					}
					if ($valid_product) {
						$price_excl_vat = $products[$i]['final_price'] * $products[$i]['quantity']; //Fred - added
						$price_incl_vat = $this->product_price($t_prid); //Fred - added
						$valid_array[] = array('product_id' => $t_prid, 'products_price' => $price_excl_vat, 'products_tax_class' => $cc_result->fields['products_tax_class_id']); //jason //Fred - changed from $products[$i]['final_price'] 'products_tax_class' => $cc_result['products_tax_class_id']);
//						$total_price += $price_incl_vat; //Fred - changed
						$total_price += $price_excl_vat; // changed
					}
				}
				if (sizeof($valid_array) > 0) { // if ($valid_product) {
					if ($get_result->fields['coupon_type'] == 'P') {
						$ratio = $get_result->fields['coupon_amount']/100;
					} else {
						$ratio = $od_amount / $total_price;
					}
					if ($get_result->fields['coupon_type'] == 'S') $ratio = 1;
					if ($method=='Credit Note') {
						$tax_rate = twe_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
						$tax_desc = twe_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
						if ($get_result->fields['coupon_type'] == 'P') {
							$tod_amount = $od_amount / (100 + $tax_rate)* $tax_rate;
						} else {
							$tod_amount = $order->info['tax_groups'][$tax_desc] * $od_amount/100;
						}
						$order->info['tax_groups'][$tax_desc] -= $tod_amount;
						$order->info['total'] -= $tod_amount; //  need to modify total ...OLD
						$order->info['tax'] -= $tod_amount; //Fred - added
					} else {
						for ($p=0; $p<sizeof($valid_array); $p++) {
							$tax_rate = twe_get_tax_rate($valid_array[$p]['products_tax_class'], $order->delivery['country']['id'], $order->delivery['zone_id']);
							$tax_desc = twe_get_tax_description($valid_array[$p]['products_tax_class'], $order->delivery['country']['id'], $order->delivery['zone_id']);
							if ($tax_rate > 0) {
								//Fred $tod_amount[$tax_desc] += ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio; //OLD
								$tod_amount = ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio; // calc total tax Fred - added
								$order->info['tax_groups'][$tax_desc] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio;
								$order->info['total'] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio; // adjust total
								$order->info['tax'] -= ($valid_array[$p]['products_price'] * $tax_rate)/100 * $ratio; // adjust tax -- Fred - added
							}
						}
					}
				}
				//NO RESTRICTION--------------------------------
			} else {
				if ($get_result->fields['coupon_type'] =='F') {
					$tod_amount = 0;
					if ($method=='Credit Note') {
						$tax_rate = twe_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
						$tax_desc = twe_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
						$tod_amount = $od_amount / (100 + $tax_rate)* $tax_rate;
						$order->info['tax_groups'][$tax_desc] -= $tod_amount;
					} else {
//						$ratio1 = $od_amount/$amount;   // this produces the wrong ratipo on fixed amounts
						reset($order->info['tax_groups']);
						while (list($key, $value) = each($order->info['tax_groups'])) {
							$ratio1 = $od_amount/($amount-$order->info['tax_groups'][$key]); ////debug
							$tax_rate = twe_get_tax_rate_from_desc($key);
							$net = $tax_rate * $order->info['tax_groups'][$key];
							if ($net>0) {
								$god_amount = $order->info['tax_groups'][$key] * $ratio1;
								$tod_amount += $god_amount;
								$order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
							}
						}
					}
					$order->info['total'] -= $tod_amount; //OLD
					$order->info['tax'] -= $tod_amount; //Fred - added
			}
			if ($get_result->fields['coupon_type'] =='P') {
				$tod_amount=0;
				if ($method=='Credit Note') {
					$tax_desc = twe_get_tax_description($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
					$tod_amount = $order->info['tax_groups'][$tax_desc] * $od_amount/100;
					$order->info['tax_groups'][$tax_desc] -= $tod_amount;
				} else {
					reset($order->info['tax_groups']);
					while (list($key, $value) = each($order->info['tax_groups'])) {
						$god_amout=0;
						$tax_rate = twe_get_tax_rate_from_desc($key);
						$net = $tax_rate * $order->info['tax_groups'][$key];
						if ($net>0) {
							$god_amount = $order->info['tax_groups'][$key] * $get_result->fields['coupon_amount']/100;
							$tod_amount += $god_amount;
							$order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
						}
					}
				}
				$order->info['total'] -= $tod_amount; // have to modify total also
				$order->info['tax'] -= $tod_amount;
			}
		}
	}
}
return $tod_amount;
}

function update_credit_account($i) {
	return false;
}

function apply_credit() {
global $insert_id, $REMOTE_ADDR, $db;
	
	if ($this->deduction !=0) {
	$db->Execute("insert into " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, redeem_date, redeem_ip, customer_id, order_id) values ('" . $_SESSION['cc_id'] . "', now(), '" . $REMOTE_ADDR . "', '" . $_SESSION['customer_id'] . "', '" . $insert_id . "')");
	}
	unset($_SESSION['cc_id']);
}

function get_order_total() {
global $order, $db;
	
	$order_total = $order->info['total'];
	// Check if gift voucher is in cart and adjust total
	$products = $_SESSION['cart']->get_products();
	for ($i=0; $i<sizeof($products); $i++) {
		$t_prid = twe_get_prid($products[$i]['id']);
		$gv_query = "select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $t_prid . "'";
		$gv_result = $db->Execute($gv_query);
		if (preg_match('/^GIFT/', addslashes($gv_result->fields['products_model']))) {
			$qty = $_SESSION['cart']->get_quantity($t_prid);
			$products_tax = twe_get_tax_rate($gv_result->fields['products_tax_class_id']);
			if ($this->include_tax =='false') {
				$gv_amount = $gv_result->fields['products_price'] * $qty;
			} else {
				$gv_amount = ($gv_result->fields['products_price'] + twe_calculate_tax($gv_result->fields['products_price'],$products_tax)) * $qty;
			}
			$order_total=$order_total - $gv_amount;
		}
	}
	if ($this->include_tax == 'false') $order_total=$order_total-$order->info['tax'];
	if ($this->include_shipping == 'false') $order_total=$order_total-$order->info['shipping_cost'];
	// OK thats fine for global coupons but what about restricted coupons
	// where you can only redeem against certain products/categories.
	// and I though this was going to be easy !!!
	$coupon_result=$db->Execute("select coupon_code from " . TABLE_COUPONS . " where coupon_id='".$_SESSION['cc_id']."'");
	if ($coupon_result->RecordCount() !=0) {
		$coupon_get="select coupon_amount, coupon_minimum_order,restrict_to_products,restrict_to_categories, coupon_type from " . TABLE_COUPONS . " where coupon_code='".$coupon_result->fields['coupon_code']."'";
		$get_result=$db->Execute($coupon_get);
		$in_cat = true;
		if ($get_result->fields['restrict_to_categories']) {
			$cat_ids = preg_split("[,]", $get_result->fields['restrict_to_categories']);
			$in_cat=false;
			for ($i = 0; $i < count($cat_ids); $i++) {
				if (is_array($this->contents)) {
					reset($this->contents);
					while (list($products_id, ) = each($this->contents)) {
						$cat_query = $db->Execute("select products_id from products_to_categories where products_id = '" . $products_id . "' and categories_id = '" . $cat_ids[$i] . "'");
						if ($cat_query->RecordCount() !=0 ) {
							$in_cat = true;
							$total_price += $this->get_product_price($products_id);
						}
					}
				}
			}
		}
		$in_cart = true;
		if ($get_result->fields['restrict_to_products']) {

			$pr_ids = preg_split("[,]", $get_result->fields['restrict_to_products']);

			$in_cart=false;
			$products_array = $_SESSION['cart']->get_products();

			for ($i = 0; $i < sizeof($pr_ids); $i++) {
				for ($ii = 1; $ii<=sizeof($products_array); $ii++) {
					if (twe_get_prid($products_array[$ii-1]['id']) == $pr_ids[$i]) {
						$in_cart=true;
						$total_price += $this->get_product_price($products_array[$ii-1]['id']);
					}
				}
			}
			$order_total = $total_price;
		}
	}
return $order_total;
}

function get_product_price($product_id) {
global $order, $db;
	$products_id = twe_get_prid($product_id);
	// products price
	$qty = $_SESSION['cart']->contents[$product_id]['qty'];
	$product_query = "select products_id, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id='" . $product_id . "'";
	if ($product = $db->Execute($product_query)) {
		$prid = $product->fields['products_id'];
		$products_tax = twe_get_tax_rate($product->fields['products_tax_class_id']);
		$products_price = $product->fields['products_price'];
		$specials = $db->Execute("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $prid . "' and status = '1'");
		if ($specials->RecordCount()>0) {
			$products_price = $specials->fields['specials_new_products_price'];
		}
		if ($this->include_tax == 'true') {
			$total_price += ($products_price + twe_calculate_tax($products_price, $products_tax)) * $qty;
//			echo("total price = " . $total_price . " products_price = " . $products_price . " products_tax = " . $products_tax . "<br>");

		} else {
			$total_price += $products_price * $qty;
		}

		// attributes price
		if (isset($_SESSION['cart']->contents[$product_id]['attributes'])) {
			reset($_SESSION['cart']->contents[$product_id]['attributes']);
			while (list($option, $value) = each($_SESSION['cart']->contents[$product_id]['attributes'])) {
				$attribute_price_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $prid . "' and options_id = '" . $option . "' and options_values_id = '" . $value . "'";
				$attribute_price = $db->Execute($attribute_price_query);
				if ($attribute_price->fields['price_prefix'] == '+') {
					if ($this->include_tax == 'true') {
						$total_price += $qty * ($attribute_price->fields['options_values_price'] + twe_calculate_tax($attribute_price->fields['options_values_price'], $products_tax));
					} else {
						$total_price += $qty * ($attribute_price->fields['options_values_price']);
					}
				} else {
					if ($this->include_tax == 'true') {
						$total_price -= $qty * ($attribute_price->fields['options_values_price'] + twe_calculate_tax($attribute_price->fields['options_values_price'], $products_tax));
					} else {
						$total_price -= $qty * ($attribute_price->fields['options_values_price']);
					}
				}
			}
		}
	}
	if ($this->include_shipping == 'true') {

		$total_price += $order->info['shipping_cost'];
	}
	return $total_price;
}

//Added by Fred -- BOF -----------------------------------------------------
//JUST RETURN THE PRODUCT PRICE (INCL ATTRIBUTE PRICES) WITH OR WITHOUT TAX
function product_price($product_id) {
	$total_price = $this->get_product_price($product_id);
	if ($this->include_shipping == 'true') $total_price -= $order->info['shipping_cost'];
	return $total_price;
}
//Added by Fred -- EOF -----------------------------------------------------

function check() {
      global $db;

	if (!isset($this->check)) {
		$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_COUPON_STATUS'");
		$this->check = $check_query->RecordCount();
	}

	return $this->check;
}

function keys() {
	return array('MODULE_ORDER_TOTAL_COUPON_STATUS', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'MODULE_ORDER_TOTAL_COUPON_TAX_CLASS');
}

function install() {
      global $db;
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('', 'MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', '6', '1','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '70', '6', '2', now())");
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) values ('', 'MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING', 'true', '6', '5', 'twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) values ('', 'MODULE_ORDER_TOTAL_COUPON_INC_TAX', 'true', '6', '6','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) values ('', 'MODULE_ORDER_TOTAL_COUPON_CALC_TAX', 'None', '6', '7','twe_cfg_select_option(array(\'None\', \'Standard\', \'Credit Note\'), ', now())");
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('', 'MODULE_ORDER_TOTAL_COUPON_TAX_CLASS', '0', '6', '0', 'twe_get_tax_class_title', 'twe_cfg_pull_down_tax_classes(', now())");
}

function remove() {
      global $db;
	$keys = '';
	$keys_array = $this->keys();
	for ($i=0; $i<sizeof($keys_array); $i++) {
		$keys .= "'" . $keys_array[$i] . "',";
	}
	$keys = substr($keys, 0, -1);

	$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
	}
}
?>
