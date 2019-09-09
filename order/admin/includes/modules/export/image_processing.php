<?php
/* -----------------------------------------------------------------------------------------
   $Id: image_processing.php,v 1.3 2004/04/25 13:58:08 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cod.php,v 1.28 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (invoice.php,v 1.6 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/





  class image_processing {
    var $code, $title, $description, $enabled;


    function image_processing() {
      global $order;
      $this->code = 'image_processing';
      $this->title = MODULE_IMAGE_PROCESS_TEXT_TITLE;
      $this->description = MODULE_IMAGE_PROCESS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_IMAGE_PROCESS_SORT_ORDER;
      $this->enabled = ((MODULE_IMAGE_PROCESS_STATUS == 'True') ? true : false);

    }


    function process($file) {
         // include needed functions
require_once('includes/classes/image_manipulator.php');
        @twe_set_time_limit(0);

        // action
        // get images in original_images folder
        $files=array();

        if ($dir= opendir(DIR_FS_CATALOG_ORIGINAL_IMAGES)){
            while  (($file = readdir($dir)) !==false) {
                     if (is_file(DIR_FS_CATALOG_ORIGINAL_IMAGES.$file) and ($file !="index.html") and (strtolower($file) != "thumbs.db")){
                         $files[]=array(
                                        'id' => $file,
                                        'text' =>$file);
                     }
             }
        closedir($dir);
        }
        for ($i=0;$n=sizeof($files),$i<$n;$i++) {

          $products_image_name = $files[$i]['text'];
           if ($files[$i]['text'] != 'Thumbs.db' &&  $files[$i]['text'] != 'Index.html') {
   		   require(DIR_WS_INCLUDES . 'product_thumbnail_images.php');
           require(DIR_WS_INCLUDES . 'product_info_images.php');
           require(DIR_WS_INCLUDES . 'product_popup_images.php');
			}
         }

    }

    function display() {


    return array('text' =>
                            IMAGE_EXPORT_TYPE.'<br>'.
                            IMAGE_EXPORT.'<br>'.
                            '<br>' . twe_image_submit('button_review_approve.gif', IMAGE_UPDATE) .

                            '<a href="' . twe_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=image_processing') . '">' .
                            twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');


    }

    function check() {
	      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_IMAGE_PROCESS_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
		      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_IMAGE_PROCESS_STATUS', 'True',  '6', '1', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now())");
}

    function remove() {
		      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_IMAGE_PROCESS_STATUS');
    }

  }
?>