<?php
/* -----------------------------------------------------------------------------------------
   $Id: print_product_info.php,v 1.5 2004/01/05 15:20:26 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_info.php,v 1.94 2003/05/04); www.oscommerce.com 
   (c) 2003	 nextcommerce (print_product_info.php,v 1.16 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  // include needed functions

  $smarty = new Smarty;

  $product_info_query = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_image, pd.products_url, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['newsid'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
  $product_info = $db->Execute($product_info_query);

  
  // assign language to template for caching
  $smarty->assign('language', $_SESSION['language']);	

  $image='';
  if ($product_info->fields['products_image']!='') {
  $image=DIR_WS_CATALOG . DIR_WS_IMAGES .'news_product/'. $product_info->fields['products_image'];
  }
  $smarty->assign('PRODUCTS_NAME', $product_info->fields['products_name']);
  $smarty->assign('PRODUCTS_MODEL', $product_info->fields['products_model']);
  $smarty->assign('PRODUCTS_DESCRIPTION', $product_info->fields['products_description']);
  $smarty->assign('PRODUCTS_IMAGE',$image);
  $smarty->assign('module_content', $module_content);

  // set cache ID
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  } else {
  $smarty->caching = 1;	
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  }
  $cache_id = $_SESSION['language'] . '_' . $product_info->fields['products_id'];
  

  $smarty->display(CURRENT_TEMPLATE . '/module/print_news_product_info.html', $cache_id);
?>