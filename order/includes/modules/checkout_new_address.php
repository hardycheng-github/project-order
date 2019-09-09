<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_new_address.php,v 1.5 2004/01/07 13:27:31 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_new_address.php,v 1.3 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_new_address.php,v 1.8 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
$module_smarty=new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_radio_field.inc.php');
  //require_once(DIR_FS_INC . 'twe_get_country_list.inc.php');
  include_once(DIR_FS_INC . 'twe_get_countries_list.inc.php'); 
  include_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
  if (!isset($process)) $process = false;

  if (ACCOUNT_GENDER == 'true') {
    $male = ($gender == 'm') ? true : false;
    $female = ($gender == 'f') ? true : false;
    $module_smarty->assign('gender','1');
    $module_smarty->assign('INPUT_MALE',twe_draw_radio_field('gender', 'm', $male));
    $module_smarty->assign('INPUT_FEMALE',twe_draw_radio_field('gender', 'f', $female)  . (twe_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''));

  }
  $module_smarty->assign('INPUT_FIRSTNAME',twe_draw_input_field('firstname') . '&nbsp;' . (twe_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_LASTNAME',twe_draw_input_field('lastname') . '&nbsp;' . (twe_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''));

  if (ACCOUNT_COMPANY == 'true') {
  $module_smarty->assign('company','1');
  $module_smarty->assign('INPUT_COMPANY',twe_draw_input_field('company') . '&nbsp;' . (twe_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''));

  }
  $module_smarty->assign('INPUT_STREET',twe_draw_input_field('street_address') . '&nbsp;' . (twe_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''));

  if (ACCOUNT_SUBURB == 'true') {
  $module_smarty->assign('suburb','1');
  $module_smarty->assign('INPUT_SUBURB',twe_draw_input_field('suburb') . '&nbsp;' . (twe_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''));

  }
  $module_smarty->assign('INPUT_CODE',twe_draw_input_field('postcode') . '&nbsp;' . (twe_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_CITY',twe_draw_input_field('city') . '&nbsp;' . (twe_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_TEL',twe_draw_input_field('telephone') . '&nbsp;' . (twe_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''));
  $module_smarty->assign('INPUT_FAX',twe_draw_input_field('fax') . '&nbsp;' . (twe_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''));

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
        $entry_state = twe_draw_pull_down_menu('state', $zones_array);
      } else {
        $entry_state =  twe_draw_input_field('state');
      }
    } else {
      $entry_state =  twe_draw_input_field('state');
    }

    if (twe_not_null(ENTRY_STATE_TEXT)) $entry_state.=  '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;

$module_smarty->assign('INPUT_STATE',$entry_state);
  }
  

  $module_smarty->assign('SELECT_COUNTRY',twe_draw_pull_down_menu('country', twe_get_countries_list(), $_POST['country']) . '&nbsp;' . (twe_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''));

  $module_smarty->assign('language', $_SESSION['language']);

  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/checkout_new_address.html');

  $smarty->assign('MODULE_new_address',$module);
?>