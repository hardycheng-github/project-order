<?php
/* -----------------------------------------------------------------------------------------
   $Id: search.php,v 1.3 2004/04/25 13:58:08 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(search.php,v 1.22 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (search.php,v 1.9 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');
  require_once(DIR_FS_INC . 'twe_hide_session_id.inc.php');


    $box_smarty->assign('FORM_ACTION',twe_draw_form('quick_find', twe_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get').twe_hide_session_id());
    $box_smarty->assign('INPUT_SEARCH',twe_draw_input_field('keywords', ENTRY_PLEASE, 'size="14" maxlength="30"  onFocus="clearText(this)"'));    $box_smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_add_quick.gif', BOX_HEADING_SEARCH).'</form>');
    $box_smarty->assign('LINK_ADVANCED',twe_href_link(FILENAME_ADVANCED_SEARCH));
    $box_smarty->assign('BOX_CONTENT', $box_content);

	$box_smarty->assign('language', $_SESSION['language']);
       	  // set cache ID
  if (USE_CACHE=='false') {
  $box_smarty->caching = 0;
  $box_search= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_search.html');
  } else {
  $box_smarty->caching = 1;	
  $box_smarty->cache_lifetime=CACHE_LIFETIME;
  $box_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'];
  $box_search= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_search.html',$cache_id);
  }

    $smarty->assign('search',$box_search);
?>