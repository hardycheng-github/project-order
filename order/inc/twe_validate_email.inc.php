<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_validate_email.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(validations.php,v 1.11 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_validate_email.inc.php,v 1.5 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : twe_validate_email
  //
  // Arguments   : email   email address to be checked
  //
  // Return      : true  - valid email address
  //               false - invalid email address
  //
  // Description : function for validating email address that conforms to RFC 822 specs
  //
  //               This function is converted from a JavaScript written by
  //               Sandeep V. Tamhankar (stamhankar@hotmail.com). The original JavaScript
  //               is available at http://javascript.internet.com
  //
  // Sample Valid Addresses:
  //
  //    first.last@host.com
  //    firstlast@host.to
  //    "first last"@host.com
  //    "first@last"@host.com
  //    first-last@host.com
  //    first.last@[123.123.123.123]
  //
  // Invalid Addresses:
  //
  //    first last@host.com
  //
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////

  function twe_validate_email($email) {
    $valid_address = true;

 if (preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/", $email)) {    
          $valid_address = true;    
       }      	
  else  {
      $valid_address = false;
    }
  
    return $valid_address;
  }

?>
