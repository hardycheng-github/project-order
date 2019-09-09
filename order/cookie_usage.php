<?php
/* -----------------------------------------------------------------------------------------
   $Id: cookie_usage.php,v 1.4 2004/02/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cookie_usage.php,v 1.1 2003/03/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (cookie_usage.php,v 1.9 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
    $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  $breadcrumb->add(NAVBAR_TITLE_COOKIE_USAGE, twe_href_link(FILENAME_COOKIE_USAGE));

 require(DIR_WS_INCLUDES . 'header.php');

  $smarty->assign('BUTTON_CONTINUE','<a href="' . twe_href_link(FILENAME_DEFAULT) . '">' . twe_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>');
  $smarty->assign('language', $_SESSION['language']);


  // set cache ID
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/cookie_usage.html');
  } else {
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'];
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/cookie_usage.html',$cache_id);
  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>