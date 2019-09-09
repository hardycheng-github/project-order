<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_info.php,v 1.5 2005/04/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_info.php,v 1.94 2003/05/04); www.oscommerce.com 
   (c) 2003      nextcommerce (product_info.php,v 1.46 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist
   New Attribute Manager v4b                            Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com   
   Cross-Sell (X-Sell) Admin 1                          Autor: Joshua Dechant (dreamscape)
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
    // create smarty elements
  $smarty = new Smarty;
  
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  
  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_download.inc.php');
  require_once(DIR_FS_INC . 'twe_delete_file.inc.php');
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_attribute_price.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');

  
       // if ($_GET['action']=='get_download') {
      //  twe_get_download($_GET['cID']); 
        
       // }  
  require(DIR_WS_INCLUDES . 'header.php'); 

  include(DIR_WS_MODULES . 'product_info.php');
  //require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PRODUCT_INFO);
  if ($_SESSION['cart']->count_contents() > 0) {
	$smarty->assign('BUTTON_NEXT','<a href='.twe_href_link(FILENAME_SHOPPING_CART).'>'.twe_image_button('button_cart.png', IMAGE_BUTTON_CHECKOUT).'</a>');
  } else{
	$smarty->assign('BUTTON_NEXT',twe_image_button('button_cart.png', IMAGE_BUTTON_CHECKOUT, $button_next_disable_style));
  }
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');  
  ?>