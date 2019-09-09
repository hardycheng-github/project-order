<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_image.inc.php,v 1.2 2003/09/07 18:32:49 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_image.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
 // include needed functions
 require_once(DIR_FS_INC . 'twe_parse_input_field_data.inc.php');
 require_once(DIR_FS_INC . 'twe_not_null.inc.php');
   define('IMAGE_REQUIRED', 'true');

// The HTML image wrapper function
  function twe_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . twe_parse_input_field_data($src, array('"' => '&quot;')) . '" border="0" alt="' . twe_parse_input_field_data($alt, array('"' => '&quot;')) . '"';

    if (twe_not_null($alt)) {
      $image .= ' title=" ' . twe_parse_input_field_data($alt, array('"' => '&quot;')) . ' "';
    }

    if ( ((CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height))) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && twe_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (twe_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (twe_not_null($width) && twe_not_null($height)) {
      $image .= ' width="' . twe_parse_input_field_data($width, array('"' => '&quot;')) . '" height="' . twe_parse_input_field_data($height, array('"' => '&quot;')) . '"';
    }

    if (twe_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }
 ?>