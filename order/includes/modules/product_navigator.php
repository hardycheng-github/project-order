<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_navigator.php,v 1.4 2004/04/05 16:50:00 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


   $module_smarty = new Smarty;
   $module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');

   $products_cat_query="SELECT
                                     categories_id
                                     FROM ".TABLE_PRODUCTS_TO_CATEGORIES."
                                     WHERE products_id='".(int)$_GET['products_id']."'";
   $products_cat= $db->Execute($products_cat_query);

   // select products
   //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=" and p.products_fsk18!='1'";
  }
   $products_query="SELECT
                                 pc.products_id
                                 FROM ".TABLE_PRODUCTS_TO_CATEGORIES." pc,
                                 ".TABLE_PRODUCTS." p
                                 WHERE categories_id='".$products_cat->fields['categories_id']."'
                                 and p.products_id=pc.products_id
								 ".$fsk_lock."
                                 and p.products_status='1'";
                                 
  $products_data=$db->Execute($products_query);
  								 
   $i=0;
   
   while (!$products_data->EOF) {
   $p_data[$i]=array('pID'=>$products_data->fields['products_id']);
   if ($products_data->fields['products_id']==$_GET['products_id']) $actual_key=$i;
   $i++;
   $products_data->MoveNext();  
   }

   // check if array key = first
   if ($actual_key==0) {
   // aktuel key = first product
   } else {
   $prev_id=$actual_key-1;
   $prev_link=twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p_data[$prev_id]['pID']);
    $module_smarty->assign('PREVIOUS',$prev_link);

    // check if prev id = first
    if ($prev_id!=0) {
    $first_link=twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $p_data[0]['pID']);
      $module_smarty->assign('FIRST',$first_link);

    }
   }

   // check if key = last
   if ($actual_key==(sizeof($p_data)-1)) {
   // actual key is last
   } else {
   $next_id=$actual_key+1;
   $next_link=twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .$p_data[$next_id]['pID']);
    $module_smarty->assign('NEXT',$next_link);

    // check if next id = last
    if ($next_id!=(sizeof($p_data)-1)) {
     $last_link=twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' .$p_data[(sizeof($p_data)-1)]['pID']);
      $module_smarty->assign('LAST',$last_link);

    }
   }   
   $module_smarty->assign('PRODUCTS_COUNT',count($p_data));
   $module_smarty->assign('language', $_SESSION['language']);   
   $module_smarty->caching = 0;
   $product_navigator= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/product_navigator.html');
  $info_smarty->assign('PRODUCT_NAVIGATOR',$product_navigator);
?>