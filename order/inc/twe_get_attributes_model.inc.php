<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_attributes_model.inc.php,v 1.4 2005/04/23 12:47:31 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (twe_get_attributes_model.inc.php,v 1.1 2003/08/19); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
   
function twe_get_attributes_model($product_id, $attribute_name)
    {
global $db;
    $options_value_id_query="SELECT
                products_options_values_id
                FROM ".TABLE_PRODUCTS_OPTIONS_VALUES."
                WHERE products_options_values_name='".$attribute_name."'";
    $options_value_id_data=$db->Execute(
    $options_value_id_query);
    while (!$options_value_id_data->EOF) {
    $options_attr_query="SELECT
                attributes_model
                FROM ".TABLE_PRODUCTS_ATTRIBUTES."
                WHERE options_values_id='".$options_value_id_data->fields['products_options_values_id']."' AND products_id ='" . $product_id."'";
    $options_attr_data=$db->Execute(
    $options_attr_query);
    if ($options_attr_data->fields['attributes_model']!='') {
    return $options_attr_data->fields['attributes_model'];
    }
	$options_value_id_data->MoveNext();  
    }
    }
?>