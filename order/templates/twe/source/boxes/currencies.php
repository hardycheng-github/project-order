<?php
/* -----------------------------------------------------------------------------------------
   $Id: currencies.php,v 1.2 2004/02/17 16:20:07 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(currencies.php,v 1.16 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (currencies.php,v 1.11 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
if (substr(basename($PHP_SELF), 0, 8) != 'checkout') { 
  // include functions
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_hide_session_id.inc.php');
  if (isset($currencies) && is_object($currencies)) {

    $count_cur='';
    reset($currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($currencies->currencies)) {
    $count_cur++;
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }

    $hidden_get_variables = '';
    reset($_GET);
    while (list($key, $value) = each($_GET)) {
      if ( ($key != 'currency') && ($key != twe_session_name()) && ($key != 'x') && ($key != 'y') ) {
        $hidden_get_variables .= twe_draw_hidden_field($key, $value);
      }
    }


  }


  // dont show box if there's only 1 currency
  if ($count_cur > 1 ) {

  // reset var
  $box_smarty = new smarty;
  $box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  $box_content='';
  $box_content=twe_draw_form('currencies', twe_href_link(basename($PHP_SELF), '', $request_type, false), 'get').twe_draw_pull_down_menu('currency', $currencies_array, $_SESSION['currency'], 'onChange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . twe_hide_session_id().'</form>';


  $box_smarty->assign('BOX_CONTENT', $box_content);
  $box_smarty->assign('language', $_SESSION['language']);
    	  // set cache ID
  if (USE_CACHE=='false') {
  $box_smarty->caching = 0;
  $box_currencies= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_currencies.html');
  } else {
  $box_smarty->caching = 1;	
  $box_smarty->cache_lifetime=CACHE_LIFETIME;
  $box_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $box_currencies= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_currencies.html',$cache_id);
  }

  $smarty->assign('currencies',$box_currencies);

  }
  }
 ?>