<?php
/* -----------------------------------------------------------------------------------------
   $Id: shopping_cart.php,v 1.5 2004/02/17 21:13:26 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.32 2003/02/11); www.oscommerce.com
   (c) 2003	 nextcommerce (shopping_cart.php,v 1.21 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:

   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'twe_create_random_value.inc.php');
  require_once(DIR_FS_INC . 'twe_get_prid.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');
  require_once(DIR_FS_INC . 'twe_get_prid.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_attribute_price.inc.php');
  
  
  

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type;

    function shoppingCart() {
      $this->reset();
    }

    function restore_contents() {
      global $db;

      if (!isset($_SESSION['customer_id'])) return false;

      // insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
          $product_query = "select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products_id . "'";
          $product = $db->Execute($product_query);
          if ($product->RecordCount()<=0) {
           $db->Execute("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . $_SESSION['customer_id'] . "', '" . $products_id . "', '" . $qty . "', '" . date('Ymd') . "')");
		    if (isset($this->contents[$products_id]['attributes'])) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                $db->Execute("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . $_SESSION['customer_id'] . "', '" . $products_id . "', '" . $option . "', '" . $value . "')");
              }
            }
          } else {
            $db->Execute("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products_id . "'");
		  }
        }
      }

      // reset per-session cart contents, but not the database contents
      $this->reset(false);

     $products_query = "select products_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "'";
     $products = $db->Execute($products_query);

      while (!$products->EOF) {
        $this->contents[$products->fields['products_id']] = array('qty' => $products->fields['customers_basket_quantity']);
        // attributes
        $attributes = $db->Execute("select products_options_id, products_options_value_id from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products->fields['products_id'] . "'");
        while (!$attributes->EOF) {
          $this->contents[$products->fields['products_id']]['attributes'][$attributes->fields['products_options_id']] = $attributes->fields['products_options_value_id'];
         $attributes->MoveNext();
	    }
        $products->MoveNext();
      }

      $this->cleanup();
    }

    function reset($reset_database = false) {
      global $db;

      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

      if (isset($_SESSION['customer_id']) && ($reset_database == true)) {
       $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "'");
	   $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "'");
	  }

      unset($this->cartID);
      if (isset($_SESSION['cartID'])) unset($_SESSION['cartID']);
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
      global $db, $new_products_id_in_cart;

      $products_id = twe_get_uprid($products_id, $attributes);
      if ($notify == true) {
        $_SESSION['new_products_id_in_cart'] = $products_id;
      }

      if ($this->in_cart($products_id)) {
        $this->update_quantity($products_id, $qty, $attributes);
      } else {
        $this->contents[] = array($products_id);
        $this->contents[$products_id] = array('qty' => $qty);
        // insert into database
        if (isset($_SESSION['customer_id'])) 
		$db->Execute("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . $_SESSION['customer_id'] . "', '" . $products_id . "', '" . $qty . "', '" . date('Ymd') . "')");

        if (is_array($attributes)) {
          reset($attributes);
          while (list($option, $value) = each($attributes)) {
            $this->contents[$products_id]['attributes'][$option] = $value;
            // insert into database
            if (isset($_SESSION['customer_id'])) $db->Execute("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . $_SESSION['customer_id'] . "', '" . $products_id . "', '" . $option . "', '" . $value . "')");
 
		  }
        }
      }
      $this->cleanup();

      // assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function update_quantity($products_id, $quantity = '', $attributes = '') {
      global $db;

      if (empty($quantity)) return true; // nothing needs to be updated if theres no quantity, so we return true..

      $this->contents[$products_id] = array('qty' => $quantity);
      // update database
      if (isset($_SESSION['customer_id'])) $db->Execute("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products_id . "'");

      if (is_array($attributes)) {
        reset($attributes);
        while (list($option, $value) = each($attributes)) {
          $this->contents[$products_id]['attributes'][$option] = $value;
          // update database
          if (isset($_SESSION['customer_id'])) $db->Execute("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . $value . "' where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products_id . "' and products_options_id = '" . $option . "'");
        }
      }
    }

    function cleanup() {
      global $db;

      reset($this->contents);
      while (list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);
          // remove from database
      if ($_SESSION['customer_id']) {
           $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $key . "'");
           $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $key . "'");
           }
        }
      }
    }

    function count_contents() {  // get total number of items in cart 
	
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }

    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }


    function in_cart($products_id) {
      if (isset($this->contents[$products_id])) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {
      global $db;

      unset($this->contents[$products_id]);
      // remove from database
      if ($_SESSION['customer_id']) {
        $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products_id . "'");
  		$db->Execute("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $products_id . "'");
      }

      // assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }

    function calculate() {
	      global $db;

      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;

      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
       $qty = $this->contents[$products_id]['qty'];



        // products price
        $product_query = "select products_id, products_price, products_discount_allowed, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id='" . twe_get_prid($products_id) . "'";
        if ($product = $db->Execute($product_query)) {
		  $products_price=twe_get_products_price($product->fields['products_id'],$price_special=0,$quantity=$qty);
		  $this->total += $products_price;
          $this->weight += ($qty * $product->fields['products_weight']);
        }

        // attributes price
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
            $attribute_price_query = "select pd.products_tax_class_id, p.options_values_price, p.price_prefix, p.options_values_weight, p.weight_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " p, " . TABLE_PRODUCTS . " pd where p.products_id = '" . $product->fields['products_id'] . "' and p.options_id = '" . $option . "' and pd.products_id = p.products_id and p.options_values_id = '" . $value . "'";
            $attribute_price = $db->Execute($attribute_price_query);

            $attribute_weight=$attribute_price->fields['options_values_weight'];
            if ($attribute_price->fields['weight_prefix'] == '+') {
              $this->weight += ($qty * $attribute_price->fields['options_values_weight']);
            } else {
              $this->weight -= ($qty * $attribute_price->fields['options_values_weight']);
            }

            if ($attribute_price->fields['price_prefix'] == '+') {
              $this->total += twe_get_products_attribute_price($attribute_price->fields['options_values_price'], $tax_class=$attribute_price->fields['products_tax_class_id'], $price_special=0, $quantity=$qty, $prefix=$attribute_price->fields['price_prefix']);
            } else {
              $this->total -= twe_get_products_attribute_price($attribute_price->fields['options_values_price'], $tax_class=$attribute_price->fields['products_tax_class_id'], $price_special=0, $quantity=$qty, $prefix=$attribute_price->fields['price_prefix']);
            }	
          }
        }
      }
      if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] != 0) {
        $this->total -= $this->total/100*$_SESSION['customers_status']['customers_status_ot_discount'];
      }

    }

    function attributes_price($products_id) {
	      global $db;
	   if (isset($this->contents[$products_id]['attributes'])) {
        reset($this->contents[$products_id]['attributes']);
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
          $attribute_price_query = "select pd.products_tax_class_id, p.options_values_price, p.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " p, " . TABLE_PRODUCTS . " pd where p.products_id = '" . $products_id . "' and p.options_id = '" . $option . "' and pd.products_id = p.products_id and p.options_values_id = '" . $value . "'";
          $attribute_price = $db->Execute($attribute_price_query);
          if ($attribute_price->fields['price_prefix'] == '+') {
            $attributes_price += twe_get_products_attribute_price($attribute_price->fields['options_values_price'], $tax_class=$attribute_price->fields['products_tax_class_id'], $price_special=0, $quantity=1, $prefix=$attribute_price->fields['price_prefix']);
          } else {
            $attributes_price -= twe_get_products_attribute_price($attribute_price->fields['options_values_price'], $tax_class=$attribute_price->fields['products_tax_class_id'], $price_special=0, $quantity=1, $prefix=$attribute_price->fields['price_prefix']);
          }
        }
      }
		
      return $attributes_price;
    }

    function get_products() {
	 global $db;

      if (!is_array($this->contents)) return false;

      $products_array = array();
      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $products_query ="select p.products_id, pd.products_name,p.products_image, p.products_model, p.products_price, p.products_discount_allowed, p.products_weight, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='" . twe_get_prid($products_id) . "' and pd.products_id = p.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "'";
         
	   if ($products = $db->Execute($products_query)) {
          $prid = $products->fields['products_id'];
          $products_price = twe_get_products_price($products->fields['products_id'], $price_special=0, $quantity=1);

          $products_array[] = array('id' => $products_id,
                                    'name' => $products->fields['products_name'],
                                    'model' => $products->fields['products_model'],
                                    'image' => $products->fields['products_image'],
                                    'price' => $products_price,
//                                    'discount_allowed' => $max_product_discount,
                                    'quantity' => $this->contents[$products_id]['qty'],
                                    'weight' => $products->fields['products_weight'],
                                    'final_price' => ($products_price + $this->attributes_price($products_id)),
//                                   'final_price' => ($products_price + $this->attributes_price($products_id)),
                                    'tax_class_id' => $products->fields['products_tax_class_id'],
                                    'attributes' => $this->contents[$products_id]['attributes']);
        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }

    function generate_cart_id($length = 5) {
      return twe_create_random_value($length, 'digits');
    }

    function get_content_type() {
	global $db;
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $virtual_check_query = "select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . $products_id . "' and pa.options_values_id = '" . $value . "' and pa.products_attributes_id = pad.products_attributes_id";
              $virtual_check = $db->Execute($virtual_check_query);

              if ($virtual_check->fields['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';
                    return $this->content_type;
                    break;

                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';
                    return $this->content_type;
                    break;

                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';
                return $this->content_type;
                break;

              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key) != "user function")
        $this->$key=$kv['value'];
      }
    }
    // GV Code Start
   // ------------------------ ICW CREDIT CLASS Gift Voucher Addittion-------------------------------Start
   // amend count_contents to show nil contents for shipping
   // as we don't want to quote for 'virtual' item
   // GLOBAL CONSTANTS if NO_COUNT_ZERO_WEIGHT is true then we don't count any product with a weight
   // which is less than or equal to MINIMUM_WEIGHT
   // otherwise we just don't count gift certificates

    function count_contents_virtual() {  // get total number of items in cart disregard gift vouchers
     global $db;
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $no_count = false;
          $gv_query = "select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'";
          $gv_result = $db->Execute($gv_query);
          if (preg_match('/^GIFT/', $gv_result->fields['products_model'])) {
            $no_count=true;
          }
          if (NO_COUNT_ZERO_WEIGHT == 1) {
            $gv_query = "select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . twe_get_prid($products_id) . "'";
            $gv_result = $db->Execute($gv_query);
            if ($gv_result->fields['products_weight']<=MINIMUM_WEIGHT) {
              $no_count=true;
            }
          }
          if (!$no_count) $total_items += $this->get_quantity($products_id);
        }
      }
      return $total_items;
    }
    // ------------------------ ICW CREDIT CLASS Gift Voucher Addittion-------------------------------End
    //GV Code End
  }

?>
