<?php
/* -----------------------------------------------------------------------------------------
   $Id: add_a_quickie.php,v 1.2 2004/02/17 16:20:07 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(add_a_quickie.php,v 1.10 2001/12/19); www.oscommerce.com   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contribution:
   Add A Quickie v1.0 Autor  Harald Ponce de Leon   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

// reset var
if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
$box_smarty = new smarty;
$box_content='';
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');

$box_smarty->assign('FORM_ACTION','<form name="quick_add" method="post" action="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=add_a_quickie', 'NONSSL') . '">');
$box_smarty->assign('INPUT_FIELD',twe_draw_input_field('quickie','','size=18'));
$box_smarty->assign('SUBMIT_BUTTON',twe_image_submit('button_add_quick.gif', BOX_HEADING_ADD_PRODUCT_ID));




    $box_smarty->assign('BOX_CONTENT', $box_content);
	$box_smarty->assign('language', $_SESSION['language']);
	  // set cache ID
  if (USE_CACHE=='false') {
  $box_smarty->caching = 0;
  $box_add_a_quickie= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_add_a_quickie.html');
  } else {
  $box_smarty->caching = 1;	
  $box_smarty->cache_lifetime=CACHE_LIFETIME;
  $box_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'];
  $box_add_a_quickie= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_add_a_quickie.html',$cache_id);
  }
  
    $smarty->assign('add_a_quickie',$box_add_a_quickie);
    
 }   
 ?>