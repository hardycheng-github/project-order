<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_news_path.inc.php,v 1.1 2005/03/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_path.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_news_path($current_news_category_id = '') {
    global $news_cPath_array,$db;

    if (twe_not_null($current_news_category_id)) {
      $cp_size = sizeof($news_cPath_array);
      if ($cp_size == 0) {
        $cPath_new = $current_news_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = "select parent_id from " . TABLE_NEWS_CATEGORIES . " where categories_id = '" . $news_cPath_array[($cp_size-1)] . "'";
        $last_category = $db->Execute($last_category_query);

        $current_category_query = "select parent_id from " . TABLE_NEWS_CATEGORIES . " where categories_id = '" . $current_news_category_id . "'";
        $current_category = $db->Execute($current_category_query);

        if ($last_category->fields['parent_id'] == $current_category->fields['parent_id']) {
          for ($i=0; $i<($cp_size-1); $i++) {
            $cPath_new .= '_' . $news_cPath_array[$i];
          }
        } else {
          for ($i=0; $i<$cp_size; $i++) {
            $cPath_new .= '_' . $news_cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_news_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    } else {
      $cPath_new = implode('_', $news_cPath_array);
    }

    return 'news_cPath=' . $cPath_new;
  }
 ?>