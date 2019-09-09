<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_draw_textarea_field.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_draw_textarea_field.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Output a form textarea field
  function twe_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . twe_parse_input_field_data($name, array('"' => '&quot;')) . '" wrap="' . twe_parse_input_field_data($wrap, array('"' => '&quot;')) . '" cols="' . twe_parse_input_field_data($width, array('"' => '&quot;')) . '" rows="' . twe_parse_input_field_data($height, array('"' => '&quot;')) . '"';
                                  
    if (twe_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= $GLOBALS[$name];
    } elseif (twe_not_null($text)) {
      $field .= $text;
    }

    $field .= '</textarea>';

    return $field;
  }
 ?>