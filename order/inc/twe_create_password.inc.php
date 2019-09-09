<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_create_password.inc.php,v 1.3 2004/02/08 16:18:03 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   
    based on: 
      (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

     function twe_RandomString($length) {
       $chars = array( 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L', 'm', 'M', 'n','N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v','V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

       $max_chars = count($chars) - 1;
       srand( (double) microtime()*1000000);

       $rand_str = '';
       for($i=0;$i<$length;$i++)
       {
         $rand_str = ( $i == 0 ) ? $chars[rand(0, $max_chars)] : $rand_str . $chars[rand(0, $max_chars)];
       }

         return $rand_str;
       }

  function twe_create_password($length) {

  	$pass=twe_RandomString($lenght);
    return md5($pass);
  }
  
  ?>