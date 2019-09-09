<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_input_validation.inc.php,v 1.3 2004/06/12 23:21:27 oldpa Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


   function twe_input_validation($var,$type,$replace_char) {

      switch($type) {
                case 'cPath':
                        $replace_param='/[^0-9_]/';
                        break;
                case 'int':
                        $replace_param='/[^0-9]/';
                        break;
                case 'char':
                        $replace_param='/[^a-zA-Z]/';
                        break;

      }

    $val=preg_replace($replace_param,$replace_char,$var);

    return $val;
   }



?>