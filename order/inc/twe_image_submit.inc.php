<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_image_submit.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_image_submit.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function twe_image_submit($image, $alt = '', $parameters = '') {

    $image_submit = '<input type="image" src="' . twe_parse_input_field_data('templates/'.CURRENT_TEMPLATE.'/buttons/' . $_SESSION['language'] . '/'. $image, array('"' => '&quot;')) . '" border="0" alt="' . twe_parse_input_field_data($alt, array('"' => '&quot;')) . '"';

    if (twe_not_null($alt)) $image_submit .= ' title=" ' . twe_parse_input_field_data($alt, array('"' => '&quot;')) . ' "';

    if (twe_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }
 ?>