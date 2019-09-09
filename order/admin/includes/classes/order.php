<?php
/* --------------------------------------------------------------
   $Id: order.php,v 1.6 2004/03/02 13:28:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.twCopyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(order.php,v 1.6 2003/02/06); www.oscommerce.com 
   (c) 2003	 nextcommerce (order.php,v 1.12 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contribution:

   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  class order {
    var $info, $totals, $products, $customer, $delivery;

    function order($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }

    function query($order_id) {
	   global $db;
      $order_query = "select customers_name,
                                   customers_cid,
                                   customers_id,
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
                                   comments,
                                   currency,
                                   currency_value,
                                   date_purchased,
                                   orders_status,
                                   last_modified,
                                  customers_status,
                                  customers_status_name,
                                  customers_status_image,
                                  customers_ip,
                                  language,
                                  customers_status_discount
                                  from " . TABLE_ORDERS . " where
                                  orders_id = '" . twe_db_input($order_id) . "'";

      $order = $db->Execute($order_query);

      $totals = $db->Execute("select title, text from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . twe_db_input($order_id) . "' order by sort_order");
      while (!$totals->EOF) {
        $this->totals[] = array('title' => $totals->fields['title'],
                                'text' => $totals->fields['text']);
      $totals->MoveNext();
	  }

      $this->info = array('currency' => $order->fields['currency'],
                          'currency_value' => $order->fields['currency_value'],
                          'payment_method' => $order->fields['payment_method'],
                          'status' => $order->fields['customers_status'],
                          'status_name' => $order->fields['customers_status_name'],
                          'status_image' => $order->fields['customers_status_image'],
                          'status_discount' => $order->fields['customers_status_discount'],
                          'cc_type' => $order->fields['cc_type'],
                          'cc_owner' => $order->fields['cc_owner'],
                          'cc_number' => $order->fields['cc_number'],
                          'cc_expires' => $order->fields['cc_expires'],
                          'comments' => $order->fields['comments'],
                          'language' => $order->fields['language'],
                          'date_purchased' => $order->fields['date_purchased'],
                          'orders_status' => $order->fields['orders_status'],
                          'last_modified' => $order->fields['last_modified']);

      $this->customer = array('name' => $order->fields['customers_name'],
                              'company' => $order->fields['customers_company'],
                              'csID' => $order->fields['customers_cid'],
                              'shop_id' => $order->fields['shop_id'], 
                              'ID' => $order->fields['customers_id'],
                              'cIP' => $order->fields['customers_ip'],
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
      $orders_products = $db->Execute("select
                                                 orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price,allow_tax, products_discount_made
                                             from
                                                 " . TABLE_ORDERS_PRODUCTS . "
                                             where
                                                 orders_id ='" . twe_db_input($order_id) . "'");

      while (!$orders_products->EOF) {
        $this->products[$index] = array('qty' => $orders_products->fields['products_quantity'],
                                        'name' => $orders_products->fields['products_name'],
                                        'model' => $orders_products->fields['products_model'],
                                        'tax' => $orders_products->fields['products_tax'],
                                        'price' => $orders_products->fields['products_price'],
                                        'discount' => $orders_products->fields['products_discount_made'],
                                        'final_price' => $orders_products->fields['final_price'],
					                    'allow_tax' => $orders_products->fields['allow_tax']);

        $subindex = 0;
        $attributes = $db->Execute("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . twe_db_input($order_id) . "' and orders_products_id = '" . $orders_products->fields['orders_products_id'] . "'");
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
        $index++;
		$orders_products->MoveNext();
      }
    }
  }
?>