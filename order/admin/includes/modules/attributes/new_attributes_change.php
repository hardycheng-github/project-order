<?php
/* --------------------------------------------------------------
   $Id: new_attributes_change.php,v 1.4 2004/02/20 12:52:59 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes_change); www.oscommerce.com 
   (c) 2003	 nextcommerce (new_attributes_change.php,v 1.8 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/ 
   require_once(DIR_FS_INC .'twe_get_tax_rate.inc.php');
   require_once(DIR_FS_INC .'twe_get_tax_class_id.inc.php');
   require_once(DIR_FS_INC .'twe_format_price.inc.php');
  // I found the easiest way to do this is just delete the current attributes & start over =)
  $delete_res = $db->Execute("SELECT products_attributes_id FROM products_attributes WHERE products_id = '" . $_POST['current_product_id'] . "'");
  while(!$delete_res ->EOF) {
      $delete_download_file = $db->Execute("SELECT products_attributes_filename FROM products_attributes_download WHERE products_attributes_id = '" . $delete_res->fields['prducts_attributes_id'] . "'");
      $db->Execute("DELETE FROM products_attributes_download WHERE products_attributes_id = '" . $delete_res->fields['products_attributes_id'] . "'");
  $delete_res->MoveNext();
  }

  $db->Execute("DELETE FROM products_attributes WHERE products_id = '" . $_POST['current_product_id'] . "'" );

  // Simple, yet effective.. loop through the selected Option Values.. find the proper price & prefix.. insert.. yadda yadda yadda.
  for ($i = 0; $i < sizeof($_POST['optionValues']); $i++) {
    $query = "SELECT * FROM products_options_values_to_products_options where products_options_values_id = '" . $_POST['optionValues'][$i] . "'";
    $line = $db->Execute($query);
    while (!$line->EOF) {
      $optionsID = $line->fields['products_options_id'];
	$line->MoveNext();  
    }

    $cv_id = $_POST['optionValues'][$i];
    $value_price =  $_POST[$cv_id . '_price'];

    if (PRICE_IS_BRUTTO=='true'){

    $value_price= ($value_price/((twe_get_tax_rate(twe_get_tax_class_id($_POST['current_product_id'])))+100)*100);
    }
          $value_price=twe_round($value_price,PRICE_PRECISION);


    $value_prefix = $_POST[$cv_id . '_prefix'];
    $value_sortorder = $_POST[$cv_id . '_sortorder'];
    $value_weight_prefix = $_POST[$cv_id . '_weight_prefix'];
    $value_model =  $_POST[$cv_id . '_model'];
    $value_stock =  $_POST[$cv_id . '_stock'];
    $value_weight =  $_POST[$cv_id . '_weight'];

    $db->Execute("INSERT INTO products_attributes (products_id, options_id, options_values_id, options_values_price, price_prefix ,attributes_model, attributes_stock, options_values_weight, weight_prefix,sortorder) VALUES ('" . $_POST['current_product_id'] . "', '" . $optionsID . "', '" . $_POST['optionValues'][$i] . "', '" . $value_price . "', '" . $value_prefix . "', '" . $value_model . "', '" . $value_stock . "', '" . $value_weight . "', '" . $value_weight_prefix . "','".$value_sortorder."')") or die(mysql_error());

    $products_attributes_id = $db->Insert_ID();

        if ($_POST[$cv_id . '_download_file'] != '') {
        $value_download_file = $_POST[$cv_id . '_download_file'];
        $value_download_expire = $_POST[$cv_id . '_download_expire'];
        $value_download_count = $_POST[$cv_id . '_download_count'];
        $db->Execute("INSERT INTO products_attributes_download (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) VALUES ('" . $products_attributes_id . "', '" . $value_download_file . "', '" . $value_download_expire . "', '" . $value_download_count . "')") or die(mysql_error());

    }
  }
?>