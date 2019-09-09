<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_show_category.inc.php,v 1.2 2004/02/22 16:15:30 oldpa Exp $

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_show_category.inc.php,v 1.4 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

     function twe_show_news_category($counter) {
    global $news_foo, $news_categories_string, $news_id;


    // image for first level

    $img_1='<img src="templates/'.CURRENT_TEMPLATE.'/img/icon_arrow3.jpg">&nbsp;';

    for ($a=0; $a<$news_foo[$counter]['level']; $a++) {

      if ($news_foo[$counter]['level']=='1') {
      $news_categories_string .= "&nbsp;-&nbsp;";
      }

      $news_categories_string .= "&nbsp;&nbsp;";

    }
    if ($news_foo[$counter]['level']=='') {
    if (strlen($news_categories_string)=='0') {
    $news_categories_string .='<table width="100%"><tr><td class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">';
    } else {
    $news_categories_string .='</td></tr></table><table width="100%"><tr><td class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">';
    }

    // image for first level

    $news_categories_string .= $img_1;
    $news_categories_string .= '<b><a href="';
    //<img src="templates/zanier/img/recht_small.gif">
    } else {
    $news_categories_string .= '<a href="';
    }
    if ($news_foo[$counter]['parent'] == 0) {
      $cPath_new = 'news_cPath=' . $counter;
    } else {
      $cPath_new = 'news_cPath=' . $news_foo[$counter]['path'];
    }

    $news_categories_string .= twe_href_link(FILENAME_NEWS, $cPath_new);
    $news_categories_string .= '">';

    if ( ($news_id) && (in_array($counter, $news_id)) ) {
      $news_categories_string .= '<b>';
    }

    // display category name
    $news_categories_string .= $news_foo[$counter]['name'];

    if ( ($news_id) && (in_array($counter, $news_id)) ) {
      $news_categories_string .= '</b>';
    }

    if (twe_news_has_category_subcategories($counter)) {
      $news_categories_string .= '';
    }
    if ($news_foo[$counter]['level']=='') {
    $news_categories_string .= '</a></b>';
    } else {
    $news_categories_string .= '</a>';
    }

    if (SHOW_COUNTS == 'true') {
      $products_in_category = twe_news_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $news_categories_string .= '&nbsp;(' . $products_in_category . ')';
      }
    }

    $news_categories_string .= '<br>';

    if ($news_foo[$counter]['next_id']) {
      twe_show_news_category($news_foo[$counter]['next_id']);
    }
  }

?>