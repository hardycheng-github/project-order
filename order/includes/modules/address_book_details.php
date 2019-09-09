<?php
/* -----------------------------------------------------------------------------------------
   $Id: address_book_details.php,v 1.6 2004/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(address_book_details.php,v 1.9 2003/05/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (address_book_details.php,v 1.9 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------/-----*/

  // include needed functions
  $module_smarty=new Smarty;
  $module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  include_once(DIR_FS_INC . 'twe_get_zone_name.inc.php');
  include_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php'); 
  include_once(DIR_FS_INC . 'twe_get_countries_list.inc.php'); 
  include_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
  if (!isset($process)) $process = false;


  if (ACCOUNT_GENDER == 'true') {
    $male = ($entry->fields['entry_gender'] == 'm') ? true : false;
    $female = ($entry->fields['entry_gender'] == 'f') ? true : false;

  $module_smarty->assign('gender','1');
  $module_smarty->assign('INPUT_MALE',twe_draw_radio_field('gender', 'm',$male));
  $module_smarty->assign('INPUT_FEMALE',twe_draw_radio_field('gender', 'f',$female).(twe_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''));


  }

  $module_smarty->assign('INPUT_FIRSTNAME',twe_draw_input_field('firstname',$entry->fields['entry_firstname']) . '&nbsp;' . (twe_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_LASTNAME',twe_draw_input_field('lastname',$entry->fields['entry_lastname']) . '&nbsp;' . (twe_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''));


  if (ACCOUNT_COMPANY == 'true') {
  $module_smarty->assign('company','1');
  $module_smarty->assign('INPUT_COMPANY',twe_draw_input_field('company', $entry->fields['entry_company']) . '&nbsp;' . (twe_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''));


  }


$module_smarty->assign('INPUT_STREET',twe_draw_input_field('street_address', $entry->fields['entry_street_address']) . '&nbsp;' . (twe_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''));

  if (ACCOUNT_SUBURB == 'true') {
  $module_smarty->assign('suburb','1');
  $module_smarty->assign('INPUT_SUBURB',twe_draw_input_field('suburb', $entry->fields['entry_suburb']) . '&nbsp;' . (twe_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''));

  }
  $module_smarty->assign('INPUT_CODE',twe_draw_input_field('postcode', $entry->fields['entry_postcode']) . '&nbsp;' . (twe_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_CITY',twe_draw_input_field('city', $entry->fields['entry_city']) . '&nbsp;' . (twe_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_TEL',twe_draw_input_field('telephone', $entry->fields['entry_telephone']) . '&nbsp;' . (twe_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_FAX',twe_draw_input_field('fax', $entry->fields['entry_fax']) . '&nbsp;' . (twe_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''));

  if (ACCOUNT_STATE == 'true') {
  $module_smarty->assign('state','1');


    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones = $db->Execute("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . twe_db_input($country) . "' order by zone_name");
        while (!$zones->EOF) {
          $zones_array[] = array('id' => $zones->fields['zone_name'], 'text' => $zones->fields['zone_name']);
          $zones->MoveNext();
		}
        $state_input= twe_draw_pull_down_menu('state', $zones_array);
      } else {
        $state_input= twe_draw_input_field('state');
      }
    } else {
      $state_input= twe_draw_input_field('state', twe_get_zone_name($entry->fields['entry_country_id'], $entry->fields['entry_zone_id'], $entry->fields['entry_state']));
    }

    if (twe_not_null(ENTRY_STATE_TEXT)) $state_input.= '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;

  $module_smarty->assign('INPUT_STATE',$state_input);
  }
  
  if ($_POST['country']){
  $selected = $_POST['country'];
  }else{
  $selected = $entry->fields['entry_country_id'];
  }
  $module_smarty->assign('SELECT_COUNTRY',twe_draw_pull_down_menu('country', twe_get_countries_list(), $selected) . '&nbsp;' . (twe_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''));

  if ((isset($_GET['edit']) && ($_SESSION['customer_default_address_id'] != $_GET['edit'])) || (isset($_GET['edit']) == false) ) {
  $module_smarty->assign('new','1');
  $module_smarty->assign('CHECKBOX_PRIMARY',twe_draw_checkbox_field('primary', 'on', false, 'id="primary"'));

  }

  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->caching = 0;
  $main_content=$module_smarty->fetch(CURRENT_TEMPLATE . '/module/address_book_details.html');
  $smarty->assign('MODULE_address_book_details',$main_content);
?>