<?php
/* -----------------------------------------------------------------------------------------
   $Id: order.php,v 1.11 2004/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(order.php,v 1.32 2003/02/26); www.oscommerce.com 
   (c) 2003	 nextcommerce (order.php,v 1.28 2003/08/18); www.nextcommerce.org
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

   credit card encryption functions for the catalog module
   BMC 2003 for the CC CVV Module


   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_address_format.inc.php');
  require_once(DIR_FS_INC . 'twe_get_country_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_countries.inc.php');
  require_once(DIR_FS_INC . 'twe_get_zone_code.inc.php');
  require_once(DIR_FS_INC . 'twe_get_tax_description.inc.php');
  require_once(DIR_FS_INC . 'twe_get_single_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_attribute_price_checkout.inc.php');

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type;

    function order($order_id = '') {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      if (twe_not_null($order_id)) {
        $this->query($order_id);
      } else {
        $this->cart();
      }
    }

    function query($order_id) {
   global $db;  
      $order_id = twe_db_prepare_input($order_id);

      $order_query = "select
                                   customers_id,
                                   customers_cid,
                                   customers_name,
                                   customers_company,
                                   customers_street_address,
                                   customers_suburb,
                                   customers_city,
                                   customers_postcode,
                                   customers_state,
                                   customers_country,
                                   customers_telephone,
								   customers_fax,
                                   customers_email_address,
                                   customers_address_format_id,
                                   delivery_name,
                                   delivery_company,
                                   delivery_street_address,
                                   delivery_suburb,
                                   delivery_city,
								   delivery_telephone,
								   delivery_fax,
                                   delivery_postcode,
                                   delivery_state,
                                   delivery_country,
                                   delivery_address_format_id,
                                   billing_name,
                                   billing_company,
                                   billing_street_address,
                                   billing_suburb,
                                   billing_city,
								   billing_telephone,
								   billing_fax,
                                   billing_postcode,
                                   billing_state,
                                   billing_country,
                                   billing_address_format_id,
                                   payment_method,
                                   cc_type,
                                   cc_owner,
                                   cc_number,
                                   cc_expires,
                                   cc_cvv,
                                   cc_start,
                                   cc_issue,
                                   currency,
                                   comments,
                                   currency_value,
                                   date_purchased,
                                   orders_status,
                                   last_modified
                                   from " . TABLE_ORDERS . " where
                                   orders_id = '" . (int)$order_id . "'";

      $order = $db->Execute($order_query);

      $totals = $db->Execute(
    "select title, text,value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while (!$totals->EOF) {
        $this->totals[] = array('title' => $totals->fields['title'],
                                'text' =>$totals->fields['text'],
                                'value'=>$totals->fields['value']);
		$totals->MoveNext(); 						
      }

      $order_total_query = "select text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'";
      $order_total = $db->Execute($order_total_query);

      $shipping_method_query = "select title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'";
      $shipping_method = $db->Execute($shipping_method_query);

      $order_status_query = "select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order->fields['orders_status'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
      $order_status = $db->Execute($order_status_query);

      $this->info = array('currency' => $order->fields['currency'],
                          'currency_value' => $order->fields['currency_value'],
                          'payment_method' => $order->fields['payment_method'],
                          'cc_type' => $order->fields['cc_type'],
                          'cc_owner' => $order->fields['cc_owner'],
                          'cc_number' => $order->fields['cc_number'],
                          'cc_expires' => $order->fields['cc_expires'],
// BMC CC Mod Start
                          'cc_start' => $order->fields['cc_start'],
                          'cc_issue' => $order->fields['cc_issue'],
                          'cc_cvv' => $order->fields['cc_cvv'],
// BMC CC Mod End
                          'date_purchased' => $order->fields['date_purchased'],
                          'orders_status' => $order_status->fields['orders_status_name'],
                          'last_modified' => $order->fields['last_modified'],
                          'total' => strip_tags($order_total->fields['text']),
                          'shipping_method' => ((substr($shipping_method->fields['title'], -1) == ':') ? substr(strip_tags($shipping_method->fields['title']), 0, -1) : strip_tags($shipping_method->fields['title'])),
                          'comments' => $order->fields['comments']
                          );

      $this->customer = array('id' => $order->fields['customers_id'],
                              'name' => $order->fields['customers_name'],
                              'csID' => $order->fields['customers_cid'],
                              'company' => $order->fields['customers_company'],
                              'street_address' => $order->fields['customers_street_address'],
                              'suburb' => $order->fields['customers_suburb'],
                              'city' => $order->fields['customers_city'],
                              'postcode' => $order->fields['customers_postcode'],
                              'state' => $order->fields['customers_state'],
                              'country' => $order->fields['customers_country'],
                              'format_id' => $order->fields['customers_address_format_id'],
                              'telephone' => $order->fields['customers_telephone'],
                              'fax' => $order->fields['customers_fax'],
                              'email_address' => $order->fields['customers_email_address']);

      $this->delivery = array('name' => $order->fields['delivery_name'],
                              'company' => $order->fields['delivery_company'],
                              'street_address' => $order->fields['delivery_street_address'],
                              'suburb' => $order->fields['delivery_suburb'],
                              'city' => $order->fields['delivery_city'],
                              'postcode' => $order->fields['delivery_postcode'],
                              'state' => $order->fields['delivery_state'],
                              'country' => $order->fields['delivery_country'],
							  'telephone' => $order->fields['delivery_telephone'],
                              'fax' => $order->fields['delivery_fax'],
                              'format_id' => $order->fields['delivery_address_format_id']);

      if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
        $this->delivery = false;
      }

      $this->billing = array('name' => $order->fields['billing_name'],
                             'company' => $order->fields['billing_company'],
                             'street_address' => $order->fields['billing_street_address'],
                             'suburb' => $order->fields['billing_suburb'],
                             'city' => $order->fields['billing_city'],
                             'postcode' => $order->fields['billing_postcode'],
                             'state' => $order->fields['billing_state'],
                             'country' => $order->fields['billing_country'],
							 'telephone' => $order->fields['billing_telephone'],
                             'fax' => $order->fields['billing_fax'],
                             'format_id' => $order->fields['billing_address_format_id']);

      $index = 0;
      $orders_products = $db->Execute("select orders_products_id, products_id, products_name, products_model, products_discount_made, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . twe_db_input($order_id) . "'");
      while (!$orders_products->EOF) {
        $this->products[$index] = array('qty' => $orders_products->fields['products_quantity'],
	                                	'id' => $orders_products->fields['products_id'],
                                        'name' => $orders_products->fields['products_name'],
                                        'model' => $orders_products->fields['products_model'],
                                        'tax' => $orders_products->fields['products_tax'],
					                    'price'=>$orders_products->fields['products_price'],
										'discount_allowed'=>$orders_products->fields['products_discount_made'],
                                        'final_price' => $orders_products->fields['final_price']);

        $subindex = 0;
        $attributes = $db->Execute("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . $orders_products->fields['orders_products_id'] . "'");
       if ($attributes->RecordCount()>0) { 
          while (!$attributes->EOF) {
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes->fields['products_options'],
                                                                     'value' => $attributes->fields['products_options_values'],
                                                                     'prefix' => $attributes->fields['price_prefix'],
                                                                     'price' => $attributes->fields['options_values_price']);

            $subindex++;
			$attributes->MoveNext();
          }
        }

        $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';

        $index++;
    	$orders_products->MoveNext();
      }
    }

    function cart() {
      global $currencies, $db;

      $this->content_type = $_SESSION['cart']->get_content_type();

      $customer_address_query = "select c.customers_firstname,c.customers_cid, c.customers_gender,c.customers_lastname, c.customers_telephone, c.customers_fax, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . $_SESSION['customer_id'] . "' and ab.customers_id = '" . $_SESSION['customer_id'] . "' and c.customers_default_address_id = ab.address_book_id";
      $customer_address = $db->Execute($customer_address_query);

      $shipping_address_query = "select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_telephone, ab.entry_fax, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . $_SESSION['customer_id'] . "' and ab.address_book_id = '" . $_SESSION['sendto'] . "'";
      $shipping_address = $db->Execute($shipping_address_query);
      
      $billing_address_query = "select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_telephone, ab.entry_fax, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " c on (ab.entry_country_id = c.countries_id) where ab.customers_id = '" . $_SESSION['customer_id'] . "' and ab.address_book_id = '" . $_SESSION['billto'] . "'";
      $billing_address = $db->Execute($billing_address_query);

      $tax_address_query = "select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) where ab.customers_id = '" . $_SESSION['customer_id'] . "' and ab.address_book_id = '" . ($this->content_type == 'virtual' ? $_SESSION['billto'] : $_SESSION['sendto']) . "'";
      $tax_address = $db->Execute($tax_address_query);

      $this->info = array('order_status' => DEFAULT_ORDERS_STATUS_ID,
                          'currency' => $_SESSION['currency'],
                          'currency_value' => $currencies->currencies[$_SESSION['currency']]['value'],
                          'payment_method' => $_SESSION['payment'],
                          'cc_type' => $GLOBALS['cc_type'],
                          'cc_owner' => $GLOBALS['cc_owner'],
                          'cc_number' => $GLOBALS['cc_number'],
                          'cc_expires' => $GLOBALS['cc_expires'],
// BMC CC Mod Start
                          'cc_start' => (isset($GLOBALS['cc_start']) ? $GLOBALS['cc_start'] : ''),
                          'cc_issue' => (isset($GLOBALS['cc_issue']) ? $GLOBALS['cc_issue'] : ''),
                          'cc_cvv' => (isset($GLOBALS['cc_cvv']) ? $GLOBALS['cc_cvv'] : ''),
// BMC CC Mod End
                          'shipping_method' => $_SESSION['shipping']['title'],
                          'shipping_cost' => $_SESSION['shipping']['cost'],
                          'comments' => $_SESSION['comments'],
                          'shipping_class'=>$_SESSION['shipping']['id'],
                          'payment_class' => $_SESSION['payment'],
                          );

      if (isset($_SESSION['payment']) && is_object($_SESSION['payment'])) {
        $this->info['payment_method'] = $_SESSION['payment']->title;
        $this->info['payment_class'] = $_SESSION['payment']->title;
        if ( isset($_SESSION['payment']->order_status) && is_numeric($_SESSION['payment']->order_status) && ($_SESSION['payment']->order_status > 0) ) {
          $this->info['order_status'] = $_SESSION['payment']->order_status;
        }
      }

      $this->customer = array('firstname' => $customer_address->fields['customers_firstname'],
                              'lastname' => $customer_address->fields['customers_lastname'],
                              'csID' => $customer_address->fields['customers_cid'],
                              'gender' => $customer_address->fields['customers_gender'],
                              'company' => $customer_address->fields['entry_company'],
                              'street_address' => $customer_address->fields['entry_street_address'],
                              'suburb' => $customer_address->fields['entry_suburb'],
                              'city' => $customer_address->fields['entry_city'],
                              'postcode' => $customer_address->fields['entry_postcode'],
                              'state' => ((twe_not_null($customer_address->fields['entry_state'])) ? $customer_address->fields['entry_state'] : $customer_address->fields['zone_name']),
                              'zone_id' => $customer_address->fields['entry_zone_id'],
                              'country' => array('id' => $customer_address->fields['countries_id'], 'title' => $customer_address->fields['countries_name'], 'iso_code_2' => $customer_address->fields['countries_iso_code_2'], 'iso_code_3' => $customer_address->fields['countries_iso_code_3']),
                              'format_id' => $customer_address->fields['address_format_id'],
                              'telephone' => $customer_address->fields['customers_telephone'],
                              'fax' => $customer_address->fields['customers_fax'],
                              'email_address' => $customer_address->fields['customers_email_address']);

      $this->delivery = array('firstname' => $shipping_address->fields['entry_firstname'],
                              'lastname' => $shipping_address->fields['entry_lastname'],
                              'company' => $shipping_address->fields['entry_company'],
                              'street_address' => $shipping_address->fields['entry_street_address'],
                              'suburb' => $shipping_address->fields['entry_suburb'],
                              'city' => $shipping_address->fields['entry_city'],
                              'postcode' => $shipping_address->fields['entry_postcode'],
                              'state' => ((twe_not_null($shipping_address->fields['entry_state'])) ? $shipping_address->fields['entry_state'] : $shipping_address->fields['zone_name']),
                              'zone_id' => $shipping_address->fields['entry_zone_id'],
                              'country' => array('id' => $shipping_address->fields['countries_id'], 'title' => $shipping_address->fields['countries_name'], 'iso_code_2' => $shipping_address->fields['countries_iso_code_2'], 'iso_code_3' => $shipping_address->fields['countries_iso_code_3']),
                              'country_id' => $shipping_address->fields['entry_country_id'],
							  'telephone' => $shipping_address->fields['entry_telephone'],
                              'fax' => $shipping_address->fields['entry_fax'],
                              'format_id' => $shipping_address->fields['address_format_id']);

      $this->billing = array('firstname' => $billing_address->fields['entry_firstname'],
                             'lastname' => $billing_address->fields['entry_lastname'],
                             'company' => $billing_address->fields['entry_company'],
                             'street_address' => $billing_address->fields['entry_street_address'],
                             'suburb' => $billing_address->fields['entry_suburb'],
                             'city' => $billing_address->fields['entry_city'],
                             'postcode' => $billing_address->fields['entry_postcode'],
                             'state' => ((twe_not_null($billing_address->fields['entry_state'])) ? $billing_address->fields['entry_state'] : $billing_address->fields['zone_name']),
                             'zone_id' => $billing_address->fields['entry_zone_id'],
                             'country' => array('id' => $billing_address->fields['countries_id'], 'title' => $billing_address->fields['countries_name'], 'iso_code_2' => $billing_address->fields['countries_iso_code_2'], 'iso_code_3' => $billing_address->fields['countries_iso_code_3']),
                             'country_id' => $billing_address->fields['entry_country_id'],
							 'telephone' => $billing_address->fields['entry_telephone'],
                             'fax' => $billing_address->fields['entry_fax'],
                             'format_id' => $billing_address->fields['address_format_id']);

      $index = 0;
      $products = $_SESSION['cart']->get_products();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        $this->products[$index] = array('qty' => $products[$i]['quantity'],
                                        'name' => $products[$i]['name'],
                                        'model' => $products[$i]['model'],
                                        'tax' => twe_get_tax_rate($products[$i]['tax_class_id'], $tax_address->fields['entry_country_id'], $tax_address->fields['entry_zone_id']),
                                        'tax_description' => twe_get_tax_description($products[$i]['tax_class_id'], $tax_address->fields['entry_country_id'], $tax_address->fields['entry_zone_id']),
                                        'price' =>  (twe_get_products_price($products[$i]['id'],$price_special=0,$quantity=$products[$i]['quantity'])+ twe_get_products_attribute_price_checkout($_SESSION['cart']->attributes_price($products[$i]['id']),0,0,1,'',false)*$products[$i]['quantity'])/$products[$i]['quantity'],
                            		    'final_price' => twe_get_products_price($products[$i]['id'],$price_special=0,$quantity=$products[$i]['quantity']) + twe_get_products_attribute_price_checkout($_SESSION['cart']->attributes_price($products[$i]['id']),0,0,1,'',false)*$products[$i]['quantity'],
					                    'weight' => $products[$i]['weight'],
                                        'id' => $products[$i]['id']);

        if ($products[$i]['attributes']) {
          $subindex = 0;
          reset($products[$i]['attributes']);
          while (list($option, $value) = each($products[$i]['attributes'])) {
            $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $_SESSION['languages_id'] . "' and poval.language_id = '" . $_SESSION['languages_id'] . "'";
            $attributes = $db->Execute($attributes_query);

            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes->fields['products_options_name'],
                                                                     'value' => $attributes->fields['products_options_values_name'],
                                                                     'option_id' => $option,
                                                                     'value_id' => $value,
                                                                     'prefix' => $attributes->fields['price_prefix'],
                                                                     'price' => $attributes->fields['options_values_price']);

            $subindex++;
          }
        }

        $shown_price = $this->products[$index]['final_price'];
        $this->info['subtotal'] += $shown_price;
        if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == 1){
          $shown_price_tax = $shown_price-($shown_price/100 * $_SESSION['customers_status']['customers_status_ot_discount']);
        }

        $products_tax = $this->products[$index]['tax'];
        $products_tax_description = $this->products[$index]['tax_description'];
        if ($_SESSION['customers_status']['customers_status_show_price_tax'] == '1') {
          if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == 1) {
            $this->info['tax'] += $shown_price_tax - ($shown_price_tax / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
            $this->info['tax_groups'][TAX_ADD_TAX."$products_tax_description"] += (($shown_price_tax /(100+$products_tax)) * $products_tax);
          } else {
            $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
            $this->info['tax_groups'][TAX_ADD_TAX . "$products_tax_description"] += (($shown_price /(100+$products_tax)) * $products_tax);
          }
        } else {
          if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == 1) {
            $this->info['tax'] += ($shown_price_tax/100) * ($products_tax);
            $this->info['tax_groups'][TAX_NO_TAX . "$products_tax_description"] += ($shown_price_tax/100) * ($products_tax);
          } else {
            $this->info['tax'] += ($shown_price/100) * ($products_tax);
            $this->info['tax_groups'][TAX_NO_TAX . "$products_tax_description"] += ($shown_price/100) * ($products_tax);
          }
        }

        $index++;
      }

      if ($_SESSION['customers_status']['customers_status_show_price_tax'] == '0') {
        $this->info['total'] = $this->info['subtotal']  + twe_format_price($this->info['shipping_cost'],0, true);
        if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1') {
          $this->info['total'] -= ($this->info['subtotal'] /100 * $_SESSION['customers_status']['customers_status_ot_discount']);
        }
      } else {
        $this->info['total'] = $this->info['subtotal']  + twe_format_price($this->info['shipping_cost'], 0,true);
        if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1') {
          $this->info['total'] -= ($this->info['subtotal'] /100 * $_SESSION['customers_status']['customers_status_ot_discount']);
        }
      }
    }
  }
?>