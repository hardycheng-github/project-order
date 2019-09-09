<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_draw_selection_field.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_draw_selection_field.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  
// Output a selection field - alias function for twe_draw_checkbox_field() and twe_draw_radio_field()

  function twe_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . twe_parse_input_field_data($type, array('"' => '&quot;')) . '" name="' . twe_parse_input_field_data($name, array('"' => '&quot;')) . '"';

    if (twe_not_null($value)) $selection .= ' value="' . twe_parse_input_field_data($value, array('"' => '&quot;')) . '"';

    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ( (isset($value)) && ($GLOBALS[$name] == $value) ) ) {
      $selection .= ' CHECKED';
    }

    if (twe_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }
 ?>