<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_check_news_categories_status.inc.php,v 1.1 2005/02/28 19:16:49 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   based on:
      (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/



function twe_check_news_categories_status($categories_id) {
    global $db;
         if (!$categories_id) return 0;

	$categorie_query="SELECT
                                   parent_id,
                                   categories_status
                                   FROM ".TABLE_NEWS_CATEGORIES."
                                   WHERE
                                   categories_id = '".(int)$categories_id."'";
    $categorie_data=$db->Execute($categorie_query);
    if ($categorie_data->fields['categories_status']==0) {
    return 1;
    } else {
    if ($categorie_data->fields['parent_id']!=0) {
    if (twe_check_news_categories_status($categorie_data->fields['parent_id'])>=1) return 1;
    }
    return 0;
    }
}

function twe_get_news_categoriesstatus_for_product($product_id) {

	$categorie_data=$db->Execute("SELECT
                                   categories_id
                                   FROM ".TABLE_NEWS_PRODUCTS_TO_CATEGORIES."
                                   WHERE products_id='".$product_id."'");

    while (!$categorie_data->EOF) {
          if (twe_check_news_categories_status($categorie_data->fields['categories_id'])>=1) {
          return 1;
          } else {
          return 0;
          }
          echo  $categorie_data->fields['categories_id'];
    }
}
?>