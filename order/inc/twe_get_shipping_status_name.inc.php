<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_shipping_status_name.inc.php,v 1.1 2005/04/22 13:20:29 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003     nextcommerce (twe_add_tax.inc.php,v 1.4 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

function twe_get_shipping_status_name ($shipping_status_id) {
    global $db;
         $status_query="SELECT
                                     shipping_status_name,
                                     shipping_status_image
                                     FROM ".TABLE_SHIPPING_STATUS."
                                     where shipping_status_id = '".$shipping_status_id."'
                                     and language_id = '".(int)$_SESSION['languages_id']."'";
         $status_data=$db->Execute($status_query);
         $shipping_statuses=array();
         $shipping_status=array('name'=>$status_data->fields['shipping_status_name'],'image'=>$status_data->fields['shipping_status_image']);

         return $shipping_status;
}
?>