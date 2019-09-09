<?php
/* --------------------------------------------------------------
   $Id: general.php,v 1.35 2003/08/13 23:38:04 oldpa Exp   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.156 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (general.php,v 1.35 2003/08/1); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:

   Customers Status v3.x (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Enable_Disable_Categories 1.3                Autor: Mikel Williams | mikel@ladykatcostumes.com

   Category Descriptions (Version: 1.5 MS2)    Original Author:   Brian Lowe <blowe@wpcusrgrp.org> | Editor: Lord Illicious <shaolin-venoms@illicious.net>

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/ 

  function clear_string($value) {

  $string=str_replace("'",'',$value);
  $string=str_replace(')','',$string);
  $string=str_replace('(','',$string);
  $array=explode(',',$string);
  return $array;

  }
  function check_stock($products_id) {
  global $db;
  if (STOCK_CHECK == 'true') {
    $stock_flag = '';
    $stock_query = "SELECT products_quantity FROM " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'";
    $stock_values = $db->Execute($stock_query);
    if ($stock_values->fields['products_quantity'] <= '0') {
      $stock_flag = 'true';
      $stock_warn = TEXT_WARN_MAIN;
    }

    $attribute_stock_values = $db->Execute("SELECT attributes_stock FROM " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "'");
    while (!$attribute_stock_values->EOF) {
      if ($attribute_stock_values->fields['attributes_stock'] <= '0') {
        $stock_flag = 'true';
        $stock_warn .= TEXT_WARN_ATTRIBUTE;
      }
	$attribute_stock_values->MoveNext();  
    }
    if ($stock_flag == 'true' && $products_id != '') {
      return '<td class="dataTableContent">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . ' ' . $stock_warn . '</td>';
    } else {
      return '<td class="dataTableContent">' . twe_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '</td>';
    }
    }
  }

  // Set Categorie Status
  function twe_set_categories_status($categories_id, $status) {
    global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_CATEGORIES . " set categories_status = '1' where categories_id = '" . $categories_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_CATEGORIES . " set categories_status = '0' where categories_id = '" . $categories_id . "'");
    } else {
      return -1;
    }
  }

  function twe_set_groups($categories_id,$shops) {
  global $db;
           // get products in categorie
           $products=$db->Execute("SELECT products_id FROM ".TABLE_PRODUCTS_TO_CATEGORIES." where categories_id='".$categories_id."'");
           while (!$products->EOF) {
           $db->Execute("UPDATE ".TABLE_PRODUCTS." SET group_ids='".$shops."' where products_id='".$products->fields['products_id']."'");
           $products->MoveNext();
		   }
           // set status of categorie
           $db->Execute("update " . TABLE_CATEGORIES . " set group_ids = '".$shops."' where categories_id = '" . $categories_id . "'");
           // look for deeper categories and go rekursiv
           $categories=$db->Execute("SELECT categories_id FROM ".TABLE_CATEGORIES." where parent_id='".$categories_id."'");
           while (!$categories->EOF) {
           twe_set_groups($categories->fields['categories_id'],$shops);
		   $categories->MoveNext();
           }

  }

  function twe_set_categories_rekursiv($categories_id,$status) {
  global $db;
           // get products in categorie
           $products=$db->Execute("SELECT products_id FROM ".TABLE_PRODUCTS_TO_CATEGORIES." where categories_id='".$categories_id."'");
           while (!$products->EOF) {
           $db->Execute("UPDATE ".TABLE_PRODUCTS." SET products_status='".$status."' where products_id='".$products->fields['products_id']."'");
           $products->MoveNext();
		   }
           // set status of categorie
           $db->Execute("update " . TABLE_CATEGORIES . " set categories_status = '".$status."' where categories_id = '" . $categories_id . "'");
           // look for deeper categories and go rekursiv
           $categories=$db->Execute("SELECT categories_id FROM ".TABLE_CATEGORIES." where parent_id='".$categories_id."'");
           while (!$categories->EOF) {
           twe_set_categories_rekursiv($categories->fields['categories_id'],$status);
		   $categories->MoveNext();
           }
  }


  // Set Admin Access Rights
  function twe_set_admin_access($fieldname, $status, $cID) {
    global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_ADMIN_ACCESS . " set " . $fieldname . " = '1' where customers_id = '" . $cID . "'");
    } else {
      return $db->Execute("update " . TABLE_ADMIN_ACCESS . " set " . $fieldname . " = '0' where customers_id = '" . $cID . "'");
    }
  }

  // Check whether a referer has enough permission to open an admin page
  function twe_check_permission($pagename){
    global $db;
  if ($pagename!='index') {
    $access_permission_query = "select " . $pagename . " from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . $_SESSION['customer_id'] . "'";
    $access_permission = $db->Execute($access_permission_query); 

    if (($_SESSION['customers_status']['customers_status_id'] == '0') && ($access_permission->fields[$pagename] == '1')) {
      return true;
    } else {
     return false;
    }
    } else {
    twe_redirect(twe_href_link(FILENAME_LOGIN));
    }
  }
   

  ////
  // Redirect to another page or site
  function twe_redirect($url) {
    global $logger;

    header('Location: '.$url);

    if (STORE_PAGE_PARSE_TIME == 'true') {
      if (!is_object($logger)) $logger = new logger;
      $logger->timer_stop();
    }

    exit;
  }

  function twe_customers_name($customers_id) {
     global $db;
    $customers = "select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customers_id . "'";
    $customers_values = $db->Execute($customers);

    return $customers_values->fields['customers_firstname'] . ' ' . $customers_values->fields['customers_lastname'];
  }

  function twe_get_path($current_category_id = '') {
    global $cPath_array,$db;

    if ($current_category_id == '') {
      $cPath_new = implode('_', $cPath_array);
    } else {
      if (sizeof($cPath_array) == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = "select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $cPath_array[(sizeof($cPath_array)-1)] . "'";
        $last_category = $db->Execute($last_category_query);
        $current_category_query ="select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $current_category_id . "'";
        $current_category = $db->Execute($current_category_query);
        if ($last_category->fields['parent_id'] == $current_category->fields['parent_id']) {
          for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;
        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    }

    return 'cPath=' . $cPath_new;
  }

  function twe_get_all_get_params($exclude_array = '') {
global $_GET;
    if (!is_array($exclude_array)) $exclude_array = array();
    $get_url = '';
    if (is_array($_GET) && (sizeof($_GET) > 0)) {
      reset($_GET);
      while (list($key, $value) = each($_GET)) {
        if ( (strlen($value) > 0) && ($key != twe_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
        }
      }
    }
    return $get_url;
  }

  function twe_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
  }

  ////
  // Output a raw date string in the selected locale date format
  // $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  // NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function twe_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }

  }

  function twe_datetime_short($raw_datetime) {
    if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

    $year = (int)substr($raw_datetime, 0, 4);
    $month = (int)substr($raw_datetime, 5, 2);
    $day = (int)substr($raw_datetime, 8, 2);
    $hour = (int)substr($raw_datetime, 11, 2);
    $minute = (int)substr($raw_datetime, 14, 2);
    $second = (int)substr($raw_datetime, 17, 2);

    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  }

  function twe_array_merge($array1, $array2, $array3 = '') {
    if ($array3 == '') $array3 = array();
    if (function_exists('array_merge')) {
      $array_merged = array_merge($array1, $array2, $array3);
    } else {
      while (list($key, $val) = each($array1)) $array_merged[$key] = $val;
      while (list($key, $val) = each($array2)) $array_merged[$key] = $val;
      if (sizeof($array3) > 0) while (list($key, $val) = each($array3)) $array_merged[$key] = $val;
    }

    return (array) $array_merged;
  }

  function twe_in_array($lookup_value, $lookup_array) {
    if (function_exists('in_array')) {
      if (in_array($lookup_value, $lookup_array)) return true;
    } else {
      reset($lookup_array);
      while (list($key, $value) = each($lookup_array)) {
        if ($value == $lookup_value) return true;
      }
    }

    return false;
  }

  function twe_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    global $db;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = "select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . $_SESSION['languages_id'] . "' and cd.categories_id = '" . $parent_id . "'";
      $category = $db->Execute($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category->fields['categories_name']);
    }

    $categories = $db->Execute("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "' and c.parent_id = '" . $parent_id . "' order by c.sort_order, cd.categories_name");
    while (!$categories->EOF) {
      if ($exclude != $categories->fields['categories_id']) $category_tree_array[] = array('id' => $categories->fields['categories_id'], 'text' => $spacing . $categories->fields['categories_name']);
      $category_tree_array = twe_get_category_tree($categories->fields['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
   $categories->MoveNext();
    }

    return $category_tree_array;
  }

  function twe_draw_products_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies,$db;

    if ($exclude == '') {
      $exclude = array();
    }
    $select_string = '<select name="' . $name . '"';
    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }
    $select_string .= '>';
    $products = $db->Execute("select p.products_id, pd.products_name,p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "' order by products_name");
    while (!$products->EOF) {
      if (!twe_in_array($products->fields['products_id'], $exclude)) {
      //brutto admin:
      if (PRICE_IS_BRUTTO=='true'){
      $products->fields['products_price'] = twe_round($products->fields['products_price']*((100+twe_get_tax_rate($products->fields['products_tax_class_id']))/100),PRICE_PRECISION);
      }
        $select_string .= '<option value="' . $products->fields['products_id'] . '">' . $products->fields['products_name'] . ' (' . twe_format_price($products->fields['products_price'],1,1) . ')</option>';
      }
	$products->MoveNext();  
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function twe_options_name($options_id) {
    global $db;

    $options = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_id . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $options_values = $db->Execute($options);

    return $options_values->fields['products_options_name'];
  }

  function twe_values_name($values_id) {
    global $db;

    $values = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $values_id . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $values_values = $db->Execute($values);

    return $values_values->fields['products_options_values_name'];
  }

  function twe_info_image($image, $alt, $width = '', $height = '') {
    if ( ($image) && (file_exists(DIR_FS_CATALOG_IMAGES . $image)) ) {
      $image = twe_image(DIR_WS_CATALOG_IMAGES . $image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }

    function twe_info_image_c($image, $alt, $width = '', $height = '') {
    if ( ($image) && (file_exists(DIR_FS_CATALOG_IMAGES .'categories/'. $image)) ) {
      $image = twe_image(DIR_WS_CATALOG_IMAGES .'categories/'. $image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }
  
  function twe_product_info_image($image, $alt, $width = '', $height = '') {
    if ( ($image) && (file_exists(DIR_FS_CATALOG_INFO_IMAGES . $image)) ) {
      $image = twe_image(DIR_WS_CATALOG_INFO_IMAGES . $image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }  

  function twe_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

  function twe_get_country_name($country_id) {
  global $db;
    $country = $db->Execute("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");

    if ($country->RecordCount() < 1) {
      return $country_id;
    } else {
      return $country->fields['countries_name'];
    }
  }

  function twe_get_zone_name($country_id, $zone_id, $default_zone) {
    global $db;

    $zone = $db->Execute("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if ($zone->RecordCount() >0) {
      return $zone->fields['zone_name'];
    } else {
      return $default_zone;
    }
  }
    

  function twe_browser_detect($component) {

    return stristr($_SERVER['HTTP_USER_AGENT'], $component);
  }

  function twe_tax_classes_pull_down($parameters, $selected = '') {
     global $db;
    $select_string = '<select ' . $parameters . '>';
    $classes = $db->Execute("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while (!$classes->EOF) {
      $select_string .= '<option value="' . $classes->fields['tax_class_id'] . '"';
      if ($selected == $classes->fields['tax_class_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $classes->fields['tax_class_title'] . '</option>';
   $classes->MoveNext(); 
	}
    $select_string .= '</select>';

    return $select_string;
  }

  function twe_geo_zones_pull_down($parameters, $selected = '') {
    global $db;
    $select_string = '<select ' . $parameters . '>';
    $zones = $db->Execute("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while (!$zones->EOF) {
      $select_string .= '<option value="' . $zones->fields['geo_zone_id'] . '"';
      if ($selected == $zones->fields['geo_zone_id']) $select_string .= ' SELECTED';
      $select_string .= '>' . $zones->fields['geo_zone_name'] . '</option>';
	  $zones->MoveNext();   
    }
    $select_string .= '</select>';
    return $select_string;
  }

  function twe_get_geo_zone_name($geo_zone_id) {
    global $db;
    $zones = $db->Execute("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . $geo_zone_id . "'");
    if ($zones->RecordCount() < 1) {
      $geo_zone_name = $geo_zone_id;
    } else {
      $geo_zone_name = $zones->fields['geo_zone_name'];
    }

    return $geo_zone_name;
  }
  
 function twe_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return twe_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return twe_parse_input_field_data($string, $translate);
      }
    }
  }
 
  
function twe_output_string_protected($string) {
    return twe_output_string($string, false, true);
  }


  function twe_address_format($address_format_id, $address, $html, $boln, $eoln) {
    global $db;
    $address_format = $db->Execute("select address_format as format
                             from " . TABLE_ADDRESS_FORMAT . "
                             where address_format_id = '" . (int)$address_format_id . "'");

    $company = twe_output_string_protected($address['company']);
    if (isset($address['firstname']) && twe_not_null($address['firstname'])) {
      $firstname = twe_output_string_protected($address['firstname']);
      $lastname = twe_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && twe_not_null($address['name'])) {
      $firstname = twe_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = twe_output_string_protected($address['street_address']);
    $suburb = twe_output_string_protected($address['suburb']);
    $city = twe_output_string_protected($address['city']);
    $state = twe_output_string_protected($address['state']);
	$telephone = twe_output_string_protected($address['telephone']);
	$fax = twe_output_string_protected($address['fax']);

    if (isset($address['country_id']) && twe_not_null($address['country_id'])) {
      $country = twe_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && twe_not_null($address['zone_id'])) {
        $state = twe_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && twe_not_null($address['country'])) {
      $country = twe_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = twe_output_string_protected($address['postcode']);
    $zip = $postcode;

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = twe_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format->fields['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (twe_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $address;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_get_zone_code
  //
  // Arguments   : country           country code string
  //               zone              state/province zone_id
  //               def_state         default string if zone==0
  //
  // Return      : state_prov_code   state/province code
  //
  // Description : Function to retrieve the state/province code (as in FL for Florida etc)
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function twe_get_zone_code($country, $zone, $def_state) {
      global $db;
    $state_prov_values = $db->Execute("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . $country . "' and zone_id = '" . $zone . "'");
    if ($state_prov_values->RecordCount() < 1) {
      $state_prov_code = $def_state;
    }
    else {
      $state_prov_code = $state_prov_values->fields['zone_code'];
    }
    
    return $state_prov_code;
  }

  function twe_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        $uprid = $uprid . '{' . $option . '}' . $value;
      }
    }

    return $uprid;
  }

  function twe_get_prid($uprid) {
    $pieces = explode ('{', $uprid);

    return $pieces[0];
  }

  function twe_get_languages() {
        global $db;
    $languages = $db->Execute("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while (!$languages->EOF) {
      $languages_array[] = array('id' => $languages->fields['languages_id'],
                                 'name' => $languages->fields['name'],
                                 'code' => $languages->fields['code'],
                                 'image' => $languages->fields['image'],
                                 'directory' => $languages->fields['directory']
                                );
	$languages->MoveNext();							
    }

    return $languages_array;
  }

  function twe_get_categories_name($category_id, $language_id) {
  global $db;
    $category_query = "select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);

    return $category->fields['categories_name'];
  }
  
  function twe_get_categories_name_size($category_id, $language_id) {
  global $db;
    $category_query = "select categories_name_size from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
	if($category->fields['categories_name_size'] == null || $category->fields['categories_name_size'] == 0){
		$category->fields['categories_name_size'] = $default_categories_name_size;
	}
    return $category->fields['categories_name_size'];
  }
  
  function twe_get_categories_heading_title($category_id, $language_id) {
    global $db;
    $category_query = "select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    return $category->fields['categories_heading_title'];
  }

  function twe_get_categories_description($category_id, $language_id) {
    global $db;
    $category_query = "select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_description'];
  }

  function twe_get_categories_meta_title($category_id, $language_id) {
    global $db;
    $category_query = "select categories_meta_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_meta_title'];
  }

  function twe_get_categories_meta_description($category_id, $language_id) {
      global $db;
    $category_query = "select categories_meta_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_meta_description'];
  }

  function twe_get_categories_meta_keywords($category_id, $language_id) {
        global $db;
    $category_query = "select categories_meta_keywords from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_meta_keywords'];
  }

  function twe_get_orders_status_name($orders_status_id, $language_id = '') {
      global $db;
    if (!$language_id) $language_id = $_SESSION['languages_id'];
    $orders_status_query = "select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $orders_status_id . "' and language_id = '" . $language_id . "'";
    $orders_status = $db->Execute($orders_status_query);

    return $orders_status->fields['orders_status_name'];
  }

    function twe_get_shipping_status_name($shipping_status_id, $language_id = '') {
      global $db;
    if (!$language_id) $language_id = $_SESSION['languages_id'];
    $shipping_status_query = "select shipping_status_name from " . TABLE_SHIPPING_STATUS . " where shipping_status_id = '" . $shipping_status_id . "' and language_id = '" . $language_id . "'";
    $shipping_status = $db->Execute($shipping_status_query);

    return $shipping_status->fields['shipping_status_name'];
  }

  function twe_get_orders_status() {
   global $db;
    $orders_status_array = array();
    $orders_status = $db->Execute("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by orders_status_id");
    while (!$orders_status->EOF) {
      $orders_status_array[] = array('id' => $orders_status->fields['orders_status_id'],
                                     'text' => $orders_status->fields['orders_status_name']
                                    );
	$orders_status->MoveNext();								
    }
    return $orders_status_array;
  }

    function twe_get_shipping_status() {
    global $db;
    $shipping_status_array = array();
    $shipping_status = $db->Execute("select shipping_status_id, shipping_status_name from " . TABLE_SHIPPING_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by shipping_status_id");
    while (!$shipping_status->EOF) {
      $shipping_status_array[] = array('id' => $shipping_status->fields['shipping_status_id'],
                                     'text' => $shipping_status->fields['shipping_status_name']
                                    );
	$shipping_status->MoveNext();								
    }
    return $shipping_status_array;
  }

  function twe_get_products_name($product_id, $language_id = 0) {
    global $db;
    if ($language_id == 0) $language_id = $_SESSION['languages_id'];
    $product_query = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_name'];
  }

  function twe_get_products_description($product_id, $language_id) {
      global $db;
    $product_query = "select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_description'];
  }

    function twe_get_products_short_description($product_id, $language_id) {
	      global $db;
    $product_query = "select products_short_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_short_description'];
  }

  function twe_get_products_meta_title($product_id, $language_id) {
  	      global $db;
    $product_query = "select products_meta_title from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);
    return $product->fields['products_meta_title'];
  }

  function twe_get_products_meta_description($product_id, $language_id) {
  global $db;
    $product_query = "select products_meta_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_meta_description'];
  }

  function twe_get_products_meta_keywords($product_id, $language_id) {
    global $db;
    $product_query = "select products_meta_keywords from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_meta_keywords'];
  }

  function twe_get_products_url($product_id, $language_id) {
    global $db;
    $product_query = "select products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_url'];
  }

  ////
  // Return the manufacturers URL in the needed language
  // TABLES: manufacturers_info
  function twe_get_manufacturer_url($manufacturer_id, $language_id) {
      global $db;
    $manufacturer_query = "select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . $manufacturer_id . "' and languages_id = '" . $language_id . "'";
    $manufacturer = $db->Execute($manufacturer_query);

    return $manufacturer->fields['manufacturers_url'];
  }

  ////
  // Wrapper for class_exists() function
  // This function is not available in all PHP versions so we test it before using it.
  function twe_class_exists($class_name) {
    if (function_exists('class_exists')) {
      return class_exists($class_name);
    } else {
      return true;
    }
  }

  ////
  // Count how many products exist in a category
  // TABLES: products, products_to_categories, categories
  function twe_products_in_category_count($categories_id, $include_deactivated = false) {
      global $db;
    $products_count = 0;

    if ($include_deactivated) {
      $products_query = "select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $categories_id . "'";
    } else {
      $products_query = "select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . $categories_id . "'";
    }

    $products = $db->Execute($products_query);

    $products_count += $products->fields['total'];

    $childs_query = "select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $categories_id . "'";
    $childs = $db->Execute($childs_query);
	if ($childs->RecordCount()>0) {
      while (!$childs->EOF) {
        $products_count += twe_products_in_category_count($childs->fields['categories_id'], $include_deactivated);
      $childs->MoveNext();
	  }
    }

    return $products_count;
  }

  ////
  // Count how many subcategories exist in a category
  // TABLES: categories
  function twe_childs_in_category_count($categories_id) {
        global $db;
    $categories_count = 0;

    $categories = $db->Execute("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $categories_id . "'");
    while (!$categories->EOF) {
      $categories_count++;
      $categories_count += twe_childs_in_category_count($categories->fields['categories_id']);
   $categories->MoveNext(); 
	}

    return $categories_count;
  }

  ////
  // Returns an array with countries
  // TABLES: countries
  function twe_get_countries($default = '') {
   global $db;
    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => STORE_COUNTRY,
                                 'text' => $default);
    }
    $countries = $db->Execute("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while (!$countries->EOF) {
      $countries_array[] = array('id' => $countries->fields['countries_id'],
                                 'text' => $countries->fields['countries_name']);
	$countries->MoveNext();							 
    }

    return $countries_array;
  }

  ////
  // return an array with country zones
  function twe_get_country_zones($country_id) {
            global $db;
    $zones_array = array();
    $zones = $db->Execute("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' order by zone_name");
    while (!$zones->EOF) {
      $zones_array[] = array('id' => $zones->fields['zone_id'],
                             'text' => $zones->fields['zone_name']);
	$zones->MoveNext();						 
    }

    return $zones_array;
  }

  function twe_prepare_country_zones_pull_down($country_id = '') {
    // preset the width of the drop-down for Netscape
    $pre = '';
    if ( (!twe_browser_detect('MSIE')) && (twe_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
    }

    $zones = twe_get_country_zones($country_id);

    if (sizeof($zones) > 0) {
      $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
      $zones = twe_array_merge($zones_select, $zones);
    } else {
      $zones = array(array('id' => '', 'text' => TYPE_BELOW));
      // create dummy options for Netscape to preset the height of the drop-down
      if ( (!twe_browser_detect('MSIE')) && (twe_browser_detect('Mozilla/4')) ) {
        for ($i=0; $i<9; $i++) {
          $zones[] = array('id' => '', 'text' => $pre);
        }
      }
    }

    return $zones;
  }

  ////
  // Get list of address_format_id's
  function twe_get_address_formats() {
              global $db;
    $address_format_values = $db->Execute("select address_format_id from " . TABLE_ADDRESS_FORMAT . " order by address_format_id");
    $address_format_array = array();
    while (!$address_format_values->EOF) {
      $address_format_array[] = array('id' => $address_format_values->fields['address_format_id'],
                                      'text' => $address_format_values->fields['address_format_id']);
    $address_format_values->MoveNext();
	}
    return $address_format_array;
  }

  ////
  // Alias function for Store configuration values in the Administration Tool
  function twe_cfg_pull_down_country_list($country_id) {
    return twe_draw_pull_down_menu('configuration_value', twe_get_countries(), $country_id);
  }

  function twe_cfg_pull_down_zone_list($zone_id) {
    return twe_draw_pull_down_menu('configuration_value', twe_get_country_zones(STORE_COUNTRY), $zone_id);
  }

  function twe_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
              global $db;
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class = $db->Execute("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while (!$tax_class->EOF) {
      $tax_class_array[] = array('id' => $tax_class->fields['tax_class_id'],
                                 'text' => $tax_class->fields['tax_class_title']);
	$tax_class->MoveNext();							 
    }

    return twe_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
  }

  ////
  // Function to read in text area in admin
 function twe_cfg_textarea($text) {
    return twe_draw_textarea_field('configuration_value', false, 35, 5, $text);
  }

  function twe_cfg_get_zone_name($zone_id) {
    global $db;
    $zone = $db->Execute("select zone_name from " . TABLE_ZONES . " where zone_id = '" . $zone_id . "'");
    if ($zone->RecordCount() < 1) {
      return $zone_id;
    } else {
      return $zone->fields['zone_name'];
    }
  }

  ////
  // Sets the status of a banner
  function twe_set_banner_status($banners_id, $status) {
  global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_BANNERS . " set status = '1', expires_impressions = NULL, expires_date = NULL, date_status_change = NULL where banners_id = '" . $banners_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_BANNERS . " set status = '0', date_status_change = now() where banners_id = '" . $banners_id . "'");
    } else {
      return -1;
    }
  }

  ////
  // Sets the status of a product
  function twe_set_product_status($products_id, $status) {
  global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_PRODUCTS . " set products_status = '1', products_last_modified = now() where products_id = '" . $products_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_PRODUCTS . " set products_status = '0', products_last_modified = now() where products_id = '" . $products_id . "'");
    } else {
      return -1;
    }
  }
///twe 30 oldpa
 function twe_set_product_featured($products_id, $status) {
  global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_PRODUCTS . " set products_featured = '1', products_last_modified = now() where products_id = '" . $products_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_PRODUCTS . " set products_featured = '0', products_last_modified = now() where products_id = '" . $products_id . "'");
    } else {
      return -1;
    }
  }

  ////
  // Sets the status of a product on special
  function twe_set_specials_status($specials_id, $status) {
  global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_SPECIALS . " set status = '1', expires_date = NULL, date_status_change = NULL where specials_id = '" . $specials_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_SPECIALS . " set status = '0', date_status_change = now() where specials_id = '" . $specials_id . "'");
    } else {
      return -1;
    }
  }

  ////
  // Sets timeout for the current script.
  // Cant be used in safe mode.
  function twe_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      @set_time_limit($limit);
    }
  }

  ////
  // Alias function for Store configuration values in the Administration Tool
  function twe_cfg_select_option($select_array, $key_value, $key = '') {
    for ($i = 0, $n = sizeof($select_array); $i < $n; $i++) {
      $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
      $string .= '<input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';
      if ($key_value == $select_array[$i]) $string .= ' CHECKED';
      $string .= '> ' . $select_array[$i];
    }

    return $string;
  }

  ////
  // Alias function for module configuration keys
  function twe_mod_select_option($select_array, $key_name, $key_value) {
    reset($select_array);
    while (list($key, $value) = each($select_array)) {
      if (is_int($key)) $key = $value;
      $string .= '<br><input type="radio" name="configuration[' . $key_name . ']" value="' . $key . '"';
      if ($key_value == $key) $string .= ' CHECKED';
      $string .= '> ' . $value;
    }

    return $string;
  }

  ////
  // Retreive server information
  function twe_get_system_information() {
global $db;
    $db_query = "select now() as datetime";
    $system_db = $db->Execute($db_query);

    list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);

    return array('date' => twe_datetime_short(date('Y-m-d H:i:s')),
                 'system' => $system,
                 'kernel' => $kernel,
                 'host' => $host,
                 'ip' => gethostbyname($host),
                 'uptime' => @exec('uptime'),
                 'http_server' => $_SERVER['SERVER_SOFTWARE'],
                 'php' => PHP_VERSION,
                 'zend' => (function_exists('zend_version') ? zend_version() : ''),
                 'db_server' => DB_SERVER,
                 'db_ip' => gethostbyname(DB_SERVER),
                 'db_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? mysql_get_server_info() : ''),
                 'db_date' => twe_datetime_short($system_db->fields['datetime']));
  }

  function twe_array_shift(&$array) {
    if (function_exists('array_shift')) {
      return array_shift($array);
    } else {
      $i = 0;
      $shifted_array = array();
      reset($array);
      while (list($key, $value) = each($array)) {
        if ($i > 0) {
          $shifted_array[$key] = $value;
        } else {
          $return = $array[$key];
        }
        $i++;
      }
      $array = $shifted_array;

      return $return;
    }
  }

  function twe_array_reverse($array) {
    if (function_exists('array_reverse')) {
      return array_reverse($array);
    } else {
      $reversed_array = array();
      for ($i=sizeof($array)-1; $i>=0; $i--) {
        $reversed_array[] = $array[$i];
      }
      return $reversed_array;
    }
  }

  function twe_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
global $db;
    if (!is_array($categories_array)) $categories_array = array();

    if ($from == 'product') {
      $categories = $db->Execute("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $id . "'");
      while (!$categories->EOF) {
        if ($categories->fields['categories_id'] == '0') {
          $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $category_query = "select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $categories->fields['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "'";
          $category = $db->Execute($category_query);
          $categories_array[$index][] = array('id' => $categories->fields['categories_id'], 'text' => $category->fields['categories_name']);
          if ( (twe_not_null($category->fields['parent_id'])) && ($category->fields['parent_id'] != '0') ) $categories_array = twe_generate_category_path($category->fields['parent_id'], 'category', $categories_array, $index);
          $categories_array[$index] = twe_array_reverse($categories_array[$index]);
        }
        $index++;
		$categories->MoveNext();
      }
    } elseif ($from == 'category') {
      $category_query = "select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "'";
      $category = $db->Execute($category_query);
      $categories_array[$index][] = array('id' => $id, 'text' => $category->fields['categories_name']);
      if ( (twe_not_null($category->fields['parent_id'])) && ($category->fields['parent_id'] != '0') ) $categories_array = twe_generate_category_path($category->fields['parent_id'], 'category', $categories_array, $index);
    }

    return $categories_array;
  }

  function twe_output_generated_category_path($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = twe_generate_category_path($id, $from);
    for ($i = 0, $n = sizeof($calculated_category_path); $i < $n; $i++) {
      for ($j = 0, $k = sizeof($calculated_category_path[$i]); $j < $k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }

  function twe_remove_category($category_id) {
  global $db;
    $category_image_query = "select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . twe_db_input($category_id) . "'";
    $category_image = $db->Execute($category_image_query);

    $duplicate_image_query = "select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . twe_db_input($category_image->fields['categories_image']) . "'";
    $duplicate_image = $db->Execute($duplicate_image_query);

    if ($duplicate_image->fields['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image->fields['categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $category_image->fields['categories_image']);
      }
    }

    $db->Execute("delete from " . TABLE_CATEGORIES . " where categories_id = '" . twe_db_input($category_id) . "'");
    $db->Execute("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . twe_db_input($category_id) . "'");
    $db->Execute("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . twe_db_input($category_id) . "'");

    if (USE_CACHE == 'true') {
      twe_reset_cache_block('categories');
      twe_reset_cache_block('also_purchased');
    }
  }
  
function twe_del_image_file($image) {
      if (file_exists(DIR_FS_CATALOG_POPUP_IMAGES . $image)) {
        @unlink(DIR_FS_CATALOG_POPUP_IMAGES . $image);
      }
      if (file_exists(DIR_FS_CATALOG_ORIGINAL_IMAGES . $image)) {
        @unlink(DIR_FS_CATALOG_ORIGINAL_IMAGES . $image);
      }
      if (file_exists(DIR_FS_CATALOG_THUMBNAIL_IMAGES . $image)) {
        @unlink(DIR_FS_CATALOG_THUMBNAIL_IMAGES . $image);
      }
      if (file_exists(DIR_FS_CATALOG_INFO_IMAGES . $image)) {
        @unlink(DIR_FS_CATALOG_INFO_IMAGES . $image);
      }
  }

  function twe_remove_product($product_id) {
    global $db;
    $product_image_query = "select products_image from " . TABLE_PRODUCTS . " where products_id = '" . twe_db_input($product_id) . "'";
    $product_image = $db->Execute($product_image_query);

    $duplicate_image_query = "select count(*) as total from " . TABLE_PRODUCTS . " where products_image = '" . twe_db_input($product_image->fields['products_image']) . "'";
    $duplicate_image = $db->Execute($duplicate_image_query);

    if ($duplicate_image->fields['total'] < 2) {
		twe_del_image_file($product_image->fields['products_image']);
		//delete more images
		$mo_images_values = $db->Execute("select image_name from " . TABLE_PRODUCTS_MORE_IMAGES . " where products_id = '" . twe_db_input($product_id) . "'");
		while (!$mo_images_values->EOF){
			twe_del_image_file($mo_images_values->fields['image_name']);
		$mo_images_values->MoveNext();	
		}
    }
    $db->Execute("delete from " . TABLE_SPECIALS . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_PRODUCTS . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_PRODUCTS_MORE_IMAGES . " where products_id = '" . twe_db_input($product_id) . "'");

   // $db->Execute("delete from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . twe_db_input($product_id) . "' OR xsell_id = '" . twe_db_input($product_id) . "'");
   // $db->Execute("delete from " . TABLE_PRODUCTS_TO_MASTER . " where (master_id = '" . twe_db_input($product_id) . "' or slave_id = '" . twe_db_input($product_id) . "')");

    $customers_status_array=twe_get_customers_statuses();
    for ($i=0,$n=sizeof($customers_status_array);$i<$n;$i++) {
     $db->Execute("delete from personal_offers_by_customers_status_" . $i . " where products_id = '" . twe_db_input($product_id) . "'");

    }

    $product_reviews = $db->Execute("select reviews_id from " . TABLE_REVIEWS . " where products_id = '" . twe_db_input($product_id) . "'");
    while (!$product_reviews->EOF) {
      $db->Execute("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $product_reviews->fields['reviews_id'] . "'");
    $product_reviews->MoveNext();
	}
    $db->Execute("delete from " . TABLE_REVIEWS . " where products_id = '" . twe_db_input($product_id) . "'");

    if (USE_CACHE == 'true') {
      twe_reset_cache_block('categories');
      twe_reset_cache_block('also_purchased');
    }
  }

  function twe_remove_order($order_id, $restock = false) {
      global $db;
    if ($restock == 'on') {
      $order = $db->Execute("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . twe_db_input($order_id) . "'");
      while (!$order->EOF) {
        $db->Execute("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order->fields['products_quantity'] . ", products_ordered = products_ordered - " . $order->fields['products_quantity'] . " where products_id = '" . $order->fields['products_id'] . "'");
     $order->MoveNext();
	  }
    }

    $db->Execute("delete from " . TABLE_ORDERS . " where orders_id = '" . twe_db_input($order_id) . "'");
    $db->Execute("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . twe_db_input($order_id) . "'");
    $db->Execute("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . twe_db_input($order_id) . "'");
    $db->Execute("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . twe_db_input($order_id) . "'");
    $db->Execute("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . twe_db_input($order_id) . "'");
  }

  function twe_reset_cache_block($cache_block) {
    global $cache_blocks;

    for ($i = 0, $n = sizeof($cache_blocks); $i < $n; $i++) {
      if ($cache_blocks[$i]['code'] == $cache_block) {
        if ($cache_blocks[$i]['multiple']) {
          if ($dir = @opendir(DIR_FS_CACHE)) {
            while ($cache_file = readdir($dir)) {
              $cached_file = $cache_blocks[$i]['file'];
              $languages = twe_get_languages();
              for ($j = 0, $k = sizeof($languages); $j < $k; $j++) {
                $cached_file_unlink = ereg_replace('-language', '-' . $languages[$j]['directory'], $cached_file);
                if (preg_match('/^/' . $cached_file_unlink, $cache_file)) {
                  @unlink(DIR_FS_CACHE . $cache_file);
                }
              }
            }
            closedir($dir);
          }
        } else {
          $cached_file = $cache_blocks[$i]['file'];
          $languages = twe_get_languages();
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $cached_file = ereg_replace('-language', '-' . $languages[$i]['directory'], $cached_file);
            @unlink(DIR_FS_CACHE . $cached_file);
          }
        }
        break;
      }
    }
  }

  function twe_get_file_permissions($mode) {
    // determine type
    if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
      $type = 's';
    } elseif ( ($mode & 0x4000) == 0x4000) { // directory
      $type = 'd';
    } elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
      $type = 'l';
    } elseif ( ($mode & 0x8000) == 0x8000) { // regular file
      $type = '-';
    } elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
      $type = 'b';
    } elseif ( ($mode & 0x2000) == 0x2000) { // character special file
      $type = 'c';
    } elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
      $type = 'p';
    } else { // unknown
      $type = '?';
    }

    // determine permissions
    $owner['read']    = ($mode & 00400) ? 'r' : '-';
    $owner['write']   = ($mode & 00200) ? 'w' : '-';
    $owner['execute'] = ($mode & 00100) ? 'x' : '-';
    $group['read']    = ($mode & 00040) ? 'r' : '-';
    $group['write']   = ($mode & 00020) ? 'w' : '-';
    $group['execute'] = ($mode & 00010) ? 'x' : '-';
    $world['read']    = ($mode & 00004) ? 'r' : '-';
    $world['write']   = ($mode & 00002) ? 'w' : '-';
    $world['execute'] = ($mode & 00001) ? 'x' : '-';

    // adjust for SUID, SGID and sticky bit
    if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

    return $type .
           $owner['read'] . $owner['write'] . $owner['execute'] .
           $group['read'] . $group['write'] . $group['execute'] .
           $world['read'] . $world['write'] . $world['execute'];
  }

  function twe_array_slice($array, $offset, $length = '0') {
    if (function_exists('array_slice')) {
      return array_slice($array, $offset, $length);
    } else {
      $length = abs($length);
      if ($length == 0) {
        $high = sizeof($array);
      } else {
        $high = $offset+$length;
      }

      for ($i=$offset; $i<$high; $i++) {
        $new_array[$i-$offset] = $array[$i];
      }

      return $new_array;
    }
  }

  function twe_remove($source) {
    global $messageStack, $twe_remove_error;

    if (isset($twe_remove_error)) $twe_remove_error = false;

    if (is_dir($source)) {
      $dir = dir($source);
      while ($file = $dir->read()) {
        if ( ($file != '.') && ($file != '..') ) {
          if (is_writeable($source . '/' . $file)) {
            twe_remove($source . '/' . $file);
          } else {
            $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
            $twe_remove_error = true;
          }
        }
      }
      $dir->close();

      if (is_writeable($source)) {
        rmdir($source);
      } else {
        $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
        $twe_remove_error = true;
      }
    } else {
      if (is_writeable($source)) {
        unlink($source);
      } else {
        $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
        $twe_remove_error = true;
      }
    }
  }

  ////
  // Wrapper for constant() function
  // Needed because its only available in PHP 4.0.4 and higher.
  function twe_constant($constant) {
    if (function_exists('constant')) {
      $temp = constant($constant);
    } else {
      eval("\$temp=$constant;");
    }
    return $temp;
  }

  ////
  // Output the tax percentage with optional padded decimals
  function twe_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

 

  function twe_get_tax_class_title($tax_class_id) {
        global $db;
 
    if ($tax_class_id == '0') {
      return TEXT_NONE;
    } else {
      $classes_query = "select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . $tax_class_id . "'";
      $classes = $db->Execute($classes_query);

      return $classes->fields['tax_class_title'];
    }
  }

  function twe_banner_image_extension() {
    if (function_exists('imagetypes')) {
      if (imagetypes() & IMG_PNG) {
        return 'png';
      } elseif (imagetypes() & IMG_JPG) {
        return 'jpg';
      } elseif (imagetypes() & IMG_GIF) {
        return 'gif';
      }
    } elseif (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
      return 'png';
    } elseif (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
      return 'jpg';
    } elseif (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
      return 'gif';
    }

    return false;
  }

  ////
  // Wrapper function for round()
  function twe_round($value, $precision) {
      return round($value, $precision);
  }

  ////
  // Add tax to a products price
  /*
  function twe_add_tax($price, $tax) {
    global $currencies;

    if (DISPLAY_PRICE_WITH_TAX == 'true') {
      return twe_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + twe_calculate_tax($price, $tax);
    } else {
      return twe_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }
*/
  // Calculates Tax rounding the result
  function twe_calculate_tax($price, $tax) {
    global $currencies;

    return twe_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

  function twe_call_function($function, $parameter, $object = '') {
    if ($object == '') {
      return call_user_func($function, $parameter);
    } else {
      return call_user_func(array($object, $function), $parameter);
    }
  }

  function twe_get_zone_class_title($zone_class_id) {
global $db;  
    if ($zone_class_id == '0') {
      return TEXT_NONE;
    } else {
      $classes_query = "select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . $zone_class_id . "'";
      $classes = $db->Execute($classes_query);

      return $classes->fields['geo_zone_name'];
    }
  }

function twe_cfg_pull_down_template_sets() {
$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');	
 if ($dir= opendir(DIR_FS_CATALOG.'templates/')){
 while  (($templates = readdir($dir)) !==false) {
        if (is_dir( DIR_FS_CATALOG.'templates/'."//".$templates) and ($templates !="CVS") and ($templates!=".") and ($templates !="..")) {
        $templates_array[]=array(
                        'id' => $templates,
                        'text' => $templates); 
        }
        }
        closedir($dir);
        sort($templates_array);
 return twe_draw_pull_down_menu($name, $templates_array,CURRENT_TEMPLATE);       
 }
}
 
  function twe_cfg_pull_down_zone_classes($zone_class_id, $key = '') {
  global $db;  
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $zone_class = $db->Execute("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while (!$zone_class->EOF) {
      $zone_class_array[] = array('id' => $zone_class->fields['geo_zone_id'],
                                  'text' => $zone_class->fields['geo_zone_name']);
	$zone_class->MoveNext();							  
    }

    return twe_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
  }

  function twe_cfg_pull_down_order_statuses($order_status_id, $key = '') {
  global $db;  

    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $statuses_array = array(array('id' => '0', 'text' => TEXT_DEFAULT));
    $statuses = $db->Execute("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by orders_status_name");
    while (!$statuses->EOF) {
      $statuses_array[] = array('id' => $statuses->fields['orders_status_id'],
                                'text' => $statuses->fields['orders_status_name']);
	$statuses->MoveNext();							
    }

    return twe_draw_pull_down_menu($name, $statuses_array, $order_status_id);
  }

  function twe_get_order_status_name($order_status_id, $language_id = '') {
  global $db;  

    if ($order_status_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $_SESSION['languages_id'];

    $status_query = "select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order_status_id . "' and language_id = '" . $language_id . "'";
    $status = $db->Execute($status_query);

    return $status->fields['orders_status_name'];
  }

  ////
  // Return a random value
  function twe_rand($min = null, $max = null) {
    static $seeded;

    if (!$seeded) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  // nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function twe_convert_linefeeds($from, $to, $string) {
    if ((PHP_VERSION < "4.0.5") && is_array($from)) {
      return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
    } else {
      return str_replace($from, $to, $string);
    }
  }
  
  // Return all customers statuses for a specified language_id and return an array(array())
  // Use it to make pull_down_menu, checkbox....
  function twe_get_customers_statuses() {
   global $db;

     $customers_statuses_array = array(array());
     $customers_statuses = $db->Execute("select customers_status_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount from " . TABLE_CUSTOMERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by customers_status_id");
     $i=1;        // this is changed from 0 to 1 in cs v1.2
     while (!$customers_statuses->EOF) {
       $i=$customers_statuses->fields['customers_status_id'];
       $customers_statuses_array[$i] = array('id' => $customers_statuses->fields['customers_status_id'],
                                           'text' => $customers_statuses->fields['customers_status_name'],
                                           'csa_public' => $customers_statuses->fields['customers_status_public'],
                                           'csa_image' => $customers_statuses->fields['customers_status_image'],
                                           'csa_discount' => $customers_statuses->fields['customers_status_discount'],
                                           'csa_ot_discount_flag' => $customers_statuses->fields['customers_status_ot_discount_flag'],
                                           'csa_ot_discount' => $customers_statuses->fields['customers_status_ot_discount'],
                                           'csa_graduated_prices' => $customers_statuses->fields['customers_status_graduated_prices']
                                           );
   $customers_statuses->MoveNext();
     }
    return $customers_statuses_array;
  }
  
  
  function twe_get_customer_status($customers_id) {
   global $db;
    $customer_status_array = array();
    $customer_status_query = "select customers_status, member_flag, customers_status_name, customers_status_public, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_graduated_prices  FROM " . TABLE_CUSTOMERS . " left join " . TABLE_CUSTOMERS_STATUS . " on customers_status = customers_status_id where customers_id='" . $customers_id . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $customer_status_array = $db->Execute($customer_status_query);
  return $customer_status_array;
  }

   function twe_get_customers_status_name($customers_status_id, $language_id = '') {
   global $db;

     if (!$language_id) $language_id = $_SESSION['languages_id'];
     $customers_status_query = "select customers_status_name from " . TABLE_CUSTOMERS_STATUS . " where customers_status_id = '" . $customers_status_id . "' and language_id = '" . $language_id . "'";
     $customers_status = $db->Execute($customers_status_query);
     return $customers_status->fields['customers_status_name'];
   }

  //to set customers status in admin for default value, newsletter, guest...
  function twe_cfg_pull_down_customers_status_list($customers_status_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
    return twe_draw_pull_down_menu($name, twe_get_customers_statuses(), $customers_status_id);
  }

  // Function for collecting ip
  // return all log info for a customer_id
  function twe_get_user_info($customer_id) {
     global $db;
    $user_info_array = $db->Execute("select customers_ip, customers_ip_date, customers_host, customers_advertiser, customers_referer_url FROM " . TABLE_CUSTOMERS_IP . " where customers_id = '" . $customer_id . "'");
    return $user_info_array;
  }

  //---------------------------------------------------------------kommt wieder raus spter!!
  function twe_get_uploaded_file($filename) {   
    if (isset($_FILES[$filename])) {   
      $uploaded_file = array('name' => $_FILES[$filename]['name'],   
                             'type' => $_FILES[$filename]['type'],   
                             'size' => $_FILES[$filename]['size'],   
                             'tmp_name' => $_FILES[$filename]['tmp_name']);   
    } elseif (isset($_FILES[$filename])) {
      $uploaded_file = array('name' => $_FILES[$filename]['name'],
                             'type' => $_FILES[$filename]['type'],
                             'size' => $_FILES[$filename]['size'],
                             'tmp_name' => $_FILES[$filename]['tmp_name']);
    } else {   
      $uploaded_file = array('name' => $GLOBALS[$filename . '_name'],   
                             'type' => $GLOBALS[$filename . '_type'],   
                             'size' => $GLOBALS[$filename . '_size'],   
                             'tmp_name' => $GLOBALS[$filename]);   
    }   

    return $uploaded_file;   
  } 

  function get_group_price($group_id, $product_id) {
     global $db;
    // well, first try to get group price from database
    $group_price_query = "SELECT personal_offer FROM personal_offers_by_customers_status_" . $group_id . " WHERE products_id = '" . $product_id . "'";
    $group_price_data = $db->Execute($group_price_query);
    // if we found a price, everything is ok if not, we will create new entry
    if ($group_price_data->fields['personal_offer'] == '') {
      $db->Execute("INSERT INTO personal_offers_by_customers_status_" . $group_id . " (price_id, products_id, quantity, personal_offer) VALUES ('', '" . $product_id . "', '1', '0.00')");
      $group_price_query = "SELECT personal_offer FROM personal_offers_by_customers_status_" . $group_id . " WHERE products_id = '" . $product_id . "'";
      $group_price_data =  $db->Execute($group_price_query);
    }
    return $group_price_data->fields['personal_offer'];
  }

  function format_price($price_string, $price_special, $currency, $allow_tax, $tax_rate) {
     global $db;
    $currencies_query = "SELECT
                                          symbol_left,
                                          symbol_right,
                                          decimal_places,
                                          value
                                      FROM
                                          " . TABLE_CURRENCIES . "
                                      WHERE
                                          code = '" . $currency . "'";
    $currencies_value = $db->Execute($currencies_query);
    $currencies_data = array();
    $currencies_data = array(
      'SYMBOL_LEFT' => $currencies_value->fields['symbol_left'],
      'SYMBOL_RIGHT' => $currencies_value->fields['symbol_right'],
      'DECIMAL_PLACES' => $currencies_value->fields['decimal_places'],
      'VALUE' => $currencies_value->fields['value']);

    // round price
    if ($allow_tax == 1) $price_string = $price_string/((100+$tax_rate)/100);
    $price_string = precision($price_string, $currencies_data['DECIMAL_PLACES']);
    if ($price_special == '1') {
      $price_string = $currencies_data['SYMBOL_LEFT'] . $price_string . $currencies_data['SYMBOL_RIGHT'];
    }
    return $price_string;
  }
        
  function precision($number, $places) {
    $number = number_format($number, $places, '.', '');
    return $number;
  }

  function twe_get_lang_definition($search_lang, $lang_array, $modifier) {
    $search_lang=$search_lang.$modifier;
    return $lang_array[$search_lang];
  }

  function twe_CheckExt($filename, $ext) {
    $passed = FALSE;
    $testExt = "\.".$ext."$";
    if (preg_match($testExt, $filename)) {
      $passed = TRUE;
    }
    return $passed;
  }

  function twe_get_status_users($status_id) {
     global $db; 
    $status_query = "SELECT count(customers_status) as count FROM " . TABLE_CUSTOMERS . " WHERE customers_status = '" . $status_id . "'";
    $status_data = $db->Execute($status_query);
    return $status_data->fields['count'];
  }
  function twe_mkdirs($path,$perm) {
  	
  if (is_dir($path)) {
  return true;
 } else {
  
  //$path=dirname($path);
  if (!mkdir($path,$perm)) return false;
  mkdir($path,$perm);
  return true;
}	
}

function twe_spaceUsed($dir) {
	if(is_dir($dir)) {
	if ($dh=opendir($dir)){
		while (($file=readdir($dh)) !==false) {
			if (is_dir($dir.$file) && $file !='.' && $file != '..') {
			twe_spaceUsed($dir.$file.'/');
			}else{
			$GLOBALS['total']+=filesize($dir.$file);
		}
	}
	closedir($dh);
}
}
}

  function create_coupon_code($salt="secret", $length=SECURITY_CODE_LENGTH) {
       global $db; 
    $ccid = md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    srand((double)microtime()*1000000); // seed the random number generator
    $random_start = @rand(0, (128-$length));
    $good_result = 0;
    while ($good_result == 0) {
      $id1=substr($ccid, $random_start,$length);
      $query = $db->Execute("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . $id1 . "'");
      if ($query->RecordCount() == 0) $good_result = 1;
    }
    return $id1;
  }

  // Update the Customers GV account
  function twe_gv_account_update($customer_id, $gv_id) {
        global $db; 
    $customer_gv_query = "select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'";
    $customer_gv = $db->Execute($customer_gv_query);   
	$coupon_gv_query = "select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'";
    $coupon_gv = $db->Execute($coupon_gv_query);
    if ($customer_gv->RecordCount() > 0) {
      $new_gv_amount = $customer_gv->fields['amount'] + $coupon_gv->fields['coupon_amount'];
       $gv_query = $db->Execute("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $new_gv_amount . "' where customer_id = '" . $customer_id . "'");
    } else {
      $gv_query = $db->Execute("insert into " . TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('" . $customer_id . "', '" . $coupon_gv->fields['coupon_amount'] . "')");
    }
  }

  // Output a day/month/year dropdown selector
  function twe_draw_date_selector($prefix, $date='') {
    $month_array = array();
    $month_array[1] =_JANUARY;
    $month_array[2] =_FEBRUARY;
    $month_array[3] =_MARCH;
    $month_array[4] =_APRIL;
    $month_array[5] =_MAY;
    $month_array[6] =_JUNE;
    $month_array[7] =_JULY;
    $month_array[8] =_AUGUST;
    $month_array[9] =_SEPTEMBER;
    $month_array[10] =_OCTOBER;
    $month_array[11] =_NOVEMBER;
    $month_array[12] =_DECEMBER;
    $usedate = getdate($date);
    $day = $usedate['mday'];
    $month = $usedate['mon'];
    $year = $usedate['year'];
    $date_selector = '<select name="'. $prefix .'_day">';
    for ($i=1;$i<32;$i++){
      $date_selector .= '<option value="' . $i . '"';
      if ($i==$day) $date_selector .= 'selected';
      $date_selector .= '>' . $i . '</option>';
    }
    $date_selector .= '</select>';
    $date_selector .= '<select name="'. $prefix .'_month">';
    for ($i=1;$i<13;$i++){
      $date_selector .= '<option value="' . $i . '"';
      if ($i==$month) $date_selector .= 'selected';
      $date_selector .= '>' . $month_array[$i] . '</option>';
    }
    $date_selector .= '</select>';
    $date_selector .= '<select name="'. $prefix .'_year">';
    for ($i=$year;$i<$year+20;$i++){
      $date_selector .= '<option value="' . $i . '"';
      if ($i==$year) $date_selector .= 'selected';
      $date_selector .= '>' . $i . '</option>';
    }
    $date_selector .= '</select>';
    return $date_selector;
  }
function twe_get_countries_acc($countries_id = '', $with_iso_codes = false) {
    global $db;
    $countries_array = array();
    if (twe_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = "select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name";
        $countries_values = $db->Execute($countries);
        $countries_array = array('countries_name' => $countries_values->fields['countries_name'],
                                 'countries_iso_code_2' => $countries_values->fields['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values->fields['countries_iso_code_3']);
      } else {
        $countries = "select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'";
        $countries_values = $db->Execute($countries);
        $countries_array = array('countries_name' => $countries_values->fields['countries_name']);
      }
    } else {
     $countries = "select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name";
     $countries_values = $db->Execute($countries);
	  while (!$countries_values->EOF) {
        $countries_array[] = array('countries_id' => $countries_values->fields['countries_id'],
                                   'countries_name' => $countries_values->fields['countries_name']);
      	 $countries_values->MoveNext(); 
	  }
    }
    return $countries_array;
  }

  
function twe_get_country_list($name, $selected = '', $parameters = '') {
global $db;
    $countries = twe_get_countries_acc();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return twe_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
  
 function twe_format_filesize($size) {
	$a = array("B","KB","MB","GB","TB","PB");
	
	$pos = 0;
	while ($size >= 1024) {
		$size /= 1024;
		$pos++;
	}
	return round($size,2)." ".$a[$pos];
}
 
function twe_findTitle($current_pid, $languageFilter) {
    $query = "SELECT * FROM products_description where language_id = '" . $_SESSION['languages_id'] . "' AND products_id = '" . $current_pid . "'";

    $result = mysql_query($query) or die(mysql_error());

    $matches = mysql_num_rows($result);

    if ($matches) {
      while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $productName = $line['products_name'];
      }
      return $productName;
    } else {
      return "Something isn't right....";
    }
  }
function twe_get_downloads() {

  $files=array();

  $dir=DIR_FS_CATALOG.'download/';
  if ($fp=opendir($dir)) {
  while  ($file = readdir($fp)) {
        if (is_file($dir.$file) && $file!='.htaccess'){
         $size=filesize($dir.$file);
        $files[]=array(
                        'id' => $file,
                        'text' => $file.' | '.twe_format_filesize($size),
                        'size'=>$size,
                        'date'=>date ("F d Y H:i:s.", filemtime($dir.$file)));
        }//if
        } // while
        closedir($fp);
  }
  return $files;
  }

//--------------------------------------------------------------------------------------Ende 
?>