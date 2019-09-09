<?php
/* -----------------------------------------------------------------------------------------
   $Id: news.php,v 1.19 2005/03/25 16:30:44 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(default.php,v 1.84 2003/05/07); www.oscommerce.com
   (c) 2003	 nextcommerce (default.php,v 1.13 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce (index.php,v 1.13 2003/08/17); www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php');
  define('NEWS',true);
  // the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($news_cPath) && twe_not_null($news_cPath)) {
    $categories_products_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $current_news_category_id . "'";
    $cateqories_products = $db->Execute($categories_products_query);
    if ($cateqories_products->fields['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = "select count(*) as total from " . TABLE_NEWS_CATEGORIES . " where parent_id = '" . $current_news_category_id . "'";
      $category_parent = $db->Execute($category_parent_query);
      if ($category_parent->fields['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
  require(DIR_WS_INCLUDES . 'header.php');
  include (DIR_WS_MODULES . 'news_default.php');   
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  $c_string='Powered by TWE-Commerce';
 ?>