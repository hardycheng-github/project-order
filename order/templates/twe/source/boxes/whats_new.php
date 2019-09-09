<?php
/* -----------------------------------------------------------------------------------------
   $Id: whats_new.php,v 1.3 2004/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(whats_new.php,v 1.31 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (whats_new.php,v 1.12 2003/08/21); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$box_content='';
  // include needed functions
  require_once(DIR_FS_INC . 'twe_random_select.inc.php');
  require_once(DIR_FS_INC . 'twe_rand.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');

  //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  $group_check='';
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $random_product_query = "select distinct
                                           p.products_id,
                                           p.products_image,
                                           p.products_tax_class_id,
                                           p.products_price
                                           from " . TABLE_PRODUCTS . " p, " .
                                           TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " .
                                           TABLE_CATEGORIES . " c
                                           where p.products_status=1
                                           and p.products_id = p2c.products_id
                                           and p.products_id !='".(int)$_GET['products_id']."' ".$fsk_lock."
                                           and c.categories_id = p2c.categories_id
                                           ".$group_check."
                                           and c.categories_status=1 order by
                                           p.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW ;
	
	$random_product = twe_random_select($random_product_query);
									    

    $whats_new_price = twe_get_products_price($random_product->fields['products_id'], $price_special = 1, $quantity = 1);



$random_product->fields['products_name']=twe_get_products_name($random_product->fields['products_id']);

if ($random_product->fields['products_name']!='') {

$box_content='<a href="' . twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product->fields['products_id']) . '">' . twe_image(DIR_WS_THUMBNAIL_IMAGES . $random_product->fields['products_image'], $random_product->fields['products_name'], PRODUCT_IMAGE_THUMBNAIL_WIDTH, PRODUCT_IMAGE_THUMBNAIL_HEIGHT) . '</a><br><a href="' . twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product->fields['products_id']) . '">' . $random_product->fields['products_name'] . '</a><br>' . $whats_new_price;

	$image='';
    if ($random_product->fields['products_image']!='') {
    $image=DIR_WS_THUMBNAIL_IMAGES . $random_product->fields['products_image'];
    }
    $box_smarty->assign('LINK',twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product->fields["products_id"]));
    $box_smarty->assign('IMAGE',$image);
    $box_smarty->assign('NAME',$random_product->fields['products_name']);
    $box_smarty->assign('PRICE',twe_get_products_price($random_product->fields['products_id'],$price_special=1,$quantity=1));
    $box_smarty->assign('BOX_CONTENT', $box_content);
    $box_smarty->assign('NEW_LINK',twe_href_link(FILENAME_PRODUCTS_NEW));
	$box_smarty->assign('language', $_SESSION['language']);
       	  // set cache ID
  if (USE_CACHE=='false') {
  $box_smarty->caching = 0;
  $box_whats_new= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_whatsnew.html');
  } else {
  $box_smarty->caching = 1;	
  $box_smarty->cache_lifetime=CACHE_LIFETIME;
  $box_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$random_product->fields['products_id'].$_SESSION['customers_status']['customers_status_name'];
  $box_whats_new= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_whatsnew.html',$cache_id);
  }

    $smarty->assign('whats_new',$box_whats_new);
 }
?>