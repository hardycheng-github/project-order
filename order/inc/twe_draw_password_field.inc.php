<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_draw_password_field.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_draw_password_field.inc.php,v 1.3 2003/08/1); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Output a form password field
  function twe_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return twe_draw_input_field($name, $value, $parameters, 'password', false);
  }
 ?>