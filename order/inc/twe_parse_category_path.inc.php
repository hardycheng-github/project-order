<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_parse_category_path.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_parse_category_path.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
 // include needed function
 require_once(DIR_FS_INC . 'twe_string_to_int.inc.php');
// Parse and secure the cPath parameter values
function twe_parse_category_path($cPath) {
    // make sure the category IDs are integers
    $cPath_array = array_map('twe_string_to_int', explode('_', $cPath));

    // make sure no duplicate category IDs exist which could lock the server in a loop
	return array_unique($cPath_array);
  }
 ?>
