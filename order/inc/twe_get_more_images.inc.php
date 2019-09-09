<?PHP
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_products_mo_images.inc.php,v 0.1 2004/06/25 21:47:50 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2004 TWE-Commerce
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   function twe_try_upload($file = '', $destination = '', $permissions = '777', $extensions = ''){
  	$file_object = new upload($file, $destination, $permissions, $extensions);
  	if ($file_object->filename != '') return $file_object; else return false;
  }  
  
   function twe_get_more_images($products_id = ''){
   global $db;
   $mo_query = "select image_id, image_nr, image_name from " . TABLE_PRODUCTS_MORE_IMAGES . " where products_id = '" . $products_id ."' ORDER BY image_nr";
   $row = $db->Execute($mo_query);
   while (!$row->EOF){
    $results[($row->fields['image_nr']-1)] = $row->fields;
	$row->MoveNext();
	}
   return $results;
   }   
?>