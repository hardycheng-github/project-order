<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_country_list.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_country_list.inc.php,v 1.5 2003/08/20); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

// include needed functions
  include_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
  include_once(DIR_FS_INC . 'twe_get_countries.inc.php');
  
  function twe_get_country_list($name, $selected = '', $parameters = '') {
//    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
//    Probleme mit register_globals=off -> erstmal nur auskommentiert. Kann u.U. gelöscht werden.
    $countries = twe_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return twe_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
 ?>