<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_draw_input_field_installer.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.1 2002/01/02); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_draw_input_field_installer.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_draw_input_field_installer($name, $text = '', $type = 'text', $parameters = '', $reinsert_value = true) {
    $field = '<input type="' . $type . '" name="' . $name . '"';
    if ( ($key = $GLOBALS[$name]) || ($key = $_GET[$name]) || ($key = $_POST[$name]) || ($key = $_SESSION[$name]) && ($reinsert_value) ) {
      $field .= ' value="' . $key . '"';
    } elseif ($text != '') {
      $field .= ' value="' . $text . '"';
    }
    if ($parameters) $field.= ' ' . $parameters;
    $field .= '>';

    return $field;
  }
 ?>