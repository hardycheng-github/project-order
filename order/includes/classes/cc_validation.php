<?php
/* -----------------------------------------------------------------------------------------
   $Id: cc_validation.php,v 1.1 2003/09/06 22:13:53 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cc_validation.php,v 1.3 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (cc_validation.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  class cc_validation {
    var $cc_type, $cc_number, $cc_expiry_month, $cc_expiry_year;

    function validate($number, $expiry_m, $expiry_y) {
      $this->cc_number = ereg_replace('[^0-9]', '', $number);

      if (preg_match('/^4[0-9]{12}([0-9]{3})?$/', $this->cc_number)) {
        $this->cc_type = 'Visa';
      } elseif (preg_match('/^5[1-5][0-9]{14}$/', $this->cc_number)) {
        $this->cc_type = 'Master Card';
      } elseif (preg_match('/^3[47][0-9]{13}$/', $this->cc_number)) {
        $this->cc_type = 'American Express';
      } elseif (preg_match('/^3(0[0-5]|[68][0-9])[0-9]{11}$/', $this->cc_number)) {
        $this->cc_type = 'Diners Club';
      } elseif (preg_match('/^6011[0-9]{12}$/', $this->cc_number)) {
        $this->cc_type = 'Discover';
      } elseif (preg_match('/^(3[0-9]{4}|2131|1800)[0-9]{11}$/', $this->cc_number)) {
        $this->cc_type = 'JCB';
      } elseif (preg_match('/^5610[0-9]{12}$/', $this->cc_number)) { 
        $this->cc_type = 'Australian BankCard';
      } else {
        return -1;
      }

      if (is_numeric($expiry_m) && ($expiry_m > 0) && ($expiry_m < 13)) {
        $this->cc_expiry_month = $expiry_m;
      } else {
        return -2;
      }

      $current_year = date('Y');
      $expiry_y = substr($current_year, 0, 2) . $expiry_y;
      if (is_numeric($expiry_y) && ($expiry_y >= $current_year) && ($expiry_y <= ($current_year + 10))) {
        $this->cc_expiry_year = $expiry_y;
      } else {
        return -3;
      }

      if ($expiry_y == $current_year) {
        if ($expiry_m < date('n')) {
          return -4;
        }
      }

      return $this->is_valid();
    }

    function is_valid() {
      $cardNumber = strrev($this->cc_number);
      $numSum = 0;

      for ($i=0; $i<strlen($cardNumber); $i++) {
        $currentNum = substr($cardNumber, $i, 1);

        // Double every second digit
        if ($i % 2 == 1) {
          $currentNum *= 2;
        }

        // Add digits of 2-digit numbers together
        if ($currentNum > 9) {
          $firstNum = $currentNum % 10;
          $secondNum = ($currentNum - $firstNum) / 10;
          $currentNum = $firstNum + $secondNum;
        }

        $numSum += $currentNum;
      }

      // If the total has no remainder it's OK
      return ($numSum % 10 == 0);
    }
  }
?>