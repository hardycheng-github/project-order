<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_show_category_content.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_show_category_content.inc.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  function twe_show_category_content($counter) {
    global $foo, $categories_string, $id;

    for ($a=0; $a<$foo[$counter]['level']; $a++) {
      $categories_string .= "&nbsp;&nbsp;";
    }

    $categories_string .= '<a href="';

    if ($foo[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
      $cPath_new = 'cPath=' . $foo[$counter]['path'];
    }

    $categories_string .= twe_href_link(FILENAME_DEFAULT, $cPath_new);
    $categories_string .= '">';

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string .= '<b>';
    }

    // display category name
    $categories_string .= $foo[$counter]['name'];

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string .= '</b>';
    }

    if (twe_has_category_subcategories($counter)) {
      $categories_string .= '-&gt;';
    }

    $categories_string .= '</a>';

    //if (SHOW_COUNTS == 'true') {
    //  $products_in_category = twe_count_products_in_category($counter);
    //  if ($products_in_category > 0) {
    //    $categories_string .= '&nbsp;(' . $products_in_category . ')';
    //  }
    //}

    $categories_string .= '<br>';

    if ($foo[$counter]['next_id']) {
      twe_show_category_content($foo[$counter]['next_id']);
    }
  }
?>