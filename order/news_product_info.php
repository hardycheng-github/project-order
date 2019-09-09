<?php
/* -----------------------------------------------------------------------------------------
   $Id: news_product_info.php,v 1.5 2005/03/07 23:05:09 oldpa   Exp $   

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
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
    // create smarty elements
  $smarty = new Smarty;
  
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
    define('NEWS',true);

  // include needed functions
  require_once(DIR_FS_INC.  'twe_delete_file.inc.php');
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');
       
  include(DIR_WS_MODULES . 'news_product_info.php');
  
  require(DIR_WS_INCLUDES . 'header.php'); 
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');  
  ?>