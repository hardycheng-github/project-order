<?php
/* -----------------------------------------------------------------------------------------
   $Id: default.php,v 1.27 2004/04/26 10:31:17 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(default.php,v 1.84 2003/05/07); www.oscommerce.com
   (c) 2003	 nextcommerce (default.php,v 1.11 2003/08/22); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

$default_smarty = new smarty;
$default_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$default_smarty->assign('session',session_id());
global $db, $group_check;
$main_content = '';
  // include needed functions
  require_once(DIR_FS_INC . 'twe_customer_greeting.inc.php');
  require_once(DIR_FS_INC . 'twe_get_path.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_check_categories_status.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
  if (twe_check_categories_status($current_category_id)>=1) {

  $error=CATEGORIE_NOT_FOUND;
  include(DIR_WS_MODULES . FILENAME_ERROR_HANDLER);
  } else {
 if ($category_depth == 'nested') {

   if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $category_query = "select
                                    cd.categories_description,
                                    cd.categories_name,
									cd.categories_heading_title,
                                    c.categories_template,
                                    c.categories_image from " .
                                    TABLE_CATEGORIES . " c, " .
                                    TABLE_CATEGORIES_DESCRIPTION . " cd
                                    where c.categories_id = '" . $current_category_id . "'
                                    and cd.categories_id = '" . $current_category_id . "'
                                    ".$group_check."
                                    and cd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

    $category = $db->Execute($category_query);


    if (isset($cPath) && preg_match('/_/', $cPath)) {
      // check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i = 0, $n = sizeof($category_links); $i < $n; $i++) {
        if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
        $cate = $db->Execute("select
                                          c.categories_id,
                                          cd.categories_name,
										  cd.categories_heading_title,
                                          c.categories_image,
                                          c.parent_id from " .
                                          TABLE_CATEGORIES . " c, " .
                                          TABLE_CATEGORIES_DESCRIPTION . " cd
                                          where c.categories_status = '1'
                                          and c.parent_id = '" . $category_links[$i] . "'
                                          and c.categories_id = cd.categories_id
                                          ".$group_check."
                                          and cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                          order by sort_order, cd.categories_name");
    //$category = $db->Execute($categories_query);
      if ($cate->RecordCount() < 1) {
          // do nothing, go through the loop
        } else {
	    $categories_query =  "select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$_SESSION['languages_id'] . "' order by sort_order, cd.categories_name";
        break; // we've found the deepest category the customer is in
        }
      }
    } else {
      if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
       $categories_query = "select
                                        c.categories_id,
                                        cd.categories_name,
										cd.categories_heading_title,
                                        c.categories_image,
                                        c.parent_id from " .
                                        TABLE_CATEGORIES . " c, " .
                                        TABLE_CATEGORIES_DESCRIPTION . " cd
                                        where c.categories_status = '1'
                                        and c.parent_id = '" . $current_category_id . "'
                                        and c.categories_id = cd.categories_id
                                        ".$group_check."
                                        and cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                        order by sort_order, cd.categories_name";
									
    }
    $categories = $db->Execute($categories_query);
    $rows = 0;
    while (!$categories->EOF) {
      $rows++;
      $cPath_new = twe_get_path($categories->fields['categories_id']);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
      $image='';
      if ($categories->fields['categories_image']!='') {
      $image=DIR_WS_IMAGES.'categories/'.$categories->fields['categories_image'];
      }
      $categories_content[]=array(
                  'CATEGORIES_NAME' => $categories->fields['categories_name'],
				  'CATEGORIES_HEADING_TITLE' => $categories->fields['categories_heading_title'],
                  'CATEGORIES_IMAGE' => $image,
                  'CATEGORIES_LINK' => twe_href_link(FILENAME_DEFAULT, $cPath_new),
                  'CATEGORIES_DESCRIPTION' => $categories->fields['categories_description']);

   $categories->MoveNext();
    }
 $new_products_category_id = $current_category_id;
 include(DIR_WS_MODULES .FILENAME_NEW_PRODUCTS);

    $image='';
    if ($category->fields['categories_image']!='') {
    $image=DIR_WS_IMAGES.'categories/'.$category->fields['categories_image'];
    }
    $default_smarty->assign('CATEGORIES_NAME',$category->fields['categories_name']);
    $default_smarty->assign('CATEGORIES_HEADING_TITLE', $category->fields['categories_heading_title']);

	$default_smarty->assign('CATEGORIES_IMAGE',$image);
    $default_smarty->assign('CATEGORIES_DESCRIPTION',$category->fields['categories_description']);

    $default_smarty->assign('language', $_SESSION['language']);
    $default_smarty->assign('module_content',$categories_content);

    // get default template
   if ($category->fields['categories_template']=='' or $category->fields['categories_template']=='default') {
          $files=array();
          if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/categorie_listing/')){
          while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/categorie_listing/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
        }
  $category->fields['categories_template']=$files[0]['id'];
  }

    $default_smarty->caching = 0;
    $main_content= $default_smarty->fetch(CURRENT_TEMPLATE.'/module/categorie_listing/'.$category->fields['categories_template']);
    $smarty->assign('main_content',$main_content);
  } elseif ($category_depth == 'products' || $_GET['manufacturers_id']) {
      //fsk18 lock
    $fsk_lock='';
    if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
    $fsk_lock=' and p.products_fsk18!=1';
    }
    // show the products of a specified manufacturer
    if (isset($_GET['manufacturers_id'])) {
      if (isset($_GET['filter_id']) && twe_not_null($_GET['filter_id'])) {

        // sorting query
        $sorting_query="SELECT products_sorting,
                                            products_sorting2 FROM ".
                                            TABLE_CATEGORIES."
                                            where categories_id='".(int)$_GET['filter_id']."'";
        $sorting_data=$db->Execute($sorting_query);
        if (!$sorting_data->fields['products_sorting']) $sorting_data->fields['products_sorting']='pd.products_name';
        $sorting=' ORDER BY '.$sorting_data->fields['products_sorting'].' '.$sorting_data->fields['products_sorting2'].' ';
        // We are asked to show only a specific category
          if (GROUP_CHECK=='true') {
           $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
           }
        $listing_sql = "select DISTINCT p.products_fsk18,
                               p.products_shippingtime,
                               p.products_model,
                               pd.products_name,
                               m.manufacturers_name,
                               p.products_quantity,
                               p.products_image,
                               p.products_weight,
                               pd.products_short_description,
                               pd.products_description,
                               p.products_id,
                               p.manufacturers_id,
                               p.products_price,
                               p.products_discount_allowed,
                               p.products_tax_class_id
                               from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                               where p.products_status = '1'
                               and p.manufacturers_id = m.manufacturers_id
                               and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'
                               and p.products_id = p2c.products_id
                               and pd.products_id = p2c.products_id
                               ".$group_check."
                               and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$fsk_lock."
                               and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'".$sorting;
      } else {
        // We show them all
          if (GROUP_CHECK=='true') {
          $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
          }
        $listing_sql = "select p.products_fsk18,
                        p.products_shippingtime,
                        p.products_model,
                        pd.products_name,
                        m.manufacturers_name,
                        p.products_quantity,
                        p.products_image,
                        p.products_weight,
                        pd.products_short_description,
                        pd.products_description,
                        p.products_id,
                        p.manufacturers_id,
                        p.products_price,
                        p.products_discount_allowed,
                        p.products_tax_class_id
                        from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m
                        where p.products_status = '1'
                        and pd.products_id = p.products_id
                        ".$group_check."
                        and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                        and p.manufacturers_id = m.manufacturers_id ".$fsk_lock."
                        and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
      }
    } else {
      // show the products in a given categorie
      if (isset($_GET['filter_id']) && twe_not_null($_GET['filter_id'])) {

                // sorting query
        $sorting_query="SELECT products_sorting,
                                            products_sorting2 FROM ".
                                            TABLE_CATEGORIES."
                                            where categories_id='".$current_category_id."'";
        $sorting_data=$db->Execute($sorting_query);
        if (!$sorting_data->fields['products_sorting']) $sorting_data->fields['products_sorting']='pd.products_name';
        $sorting=' ORDER BY '.$sorting_data->fields['products_sorting'].' '.$sorting_data->fields['products_sorting2'].' ';
        // We are asked to show only specific catgeory
          if (GROUP_CHECK=='true') {
          $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
          }
        $listing_sql = "select p.products_fsk18,
                               p.products_shippingtime,
                               p.products_model,
                               pd.products_name,
                               m.manufacturers_name,
                               p.products_quantity,
                               p.products_image,
                               p.products_weight,
                               pd.products_short_description,
                               pd.products_description,
                               p.products_id,
                               p.manufacturers_id,
                               p.products_price,
                               p.products_discount_allowed,
                               p.products_tax_class_id
                               from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                               where p.products_status = '1'
                               and p.manufacturers_id = m.manufacturers_id
                               and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "'
                               and p.products_id = p2c.products_id
                               and pd.products_id = p2c.products_id
                               ".$group_check."
                               and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$fsk_lock."
                               and p2c.categories_id = '" . $current_category_id . "'".$sorting;
      } else {

                      // sorting query
        $sorting_query="SELECT products_sorting,
                                            products_sorting2 FROM ".
                                            TABLE_CATEGORIES."
                                            where categories_id='".$current_category_id."'";
        $sorting_data=$db->Execute($sorting_query);
        if (!$sorting_data->fields['products_sorting']) $sorting_data->fields['products_sorting']='pd.products_name';
        $sorting=' ORDER BY '.$sorting_data->fields['products_sorting'].' '.$sorting_data->fields['products_sorting2'].' ';
        // We show them all
          if (GROUP_CHECK=='true') {
          $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
           }
        $listing_sql = "select p.products_fsk18,
                               p.products_shippingtime,
                               p.products_model,
                               pd.products_name,
                               m.manufacturers_name,
                               p.products_quantity,
                               p.products_image,
                               p.products_weight,
                               pd.products_short_description,
                               pd.products_description,
                               p.products_id,
                               p.manufacturers_id,
                               p.products_price,
                               p.products_discount_allowed,
                               p.products_tax_class_id
                               from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " .
                               TABLE_PRODUCTS . " p left join " .
                               TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id 
							   left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . 
							   TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                               where p.products_status = '1'
                               and p.products_id = p2c.products_id
                               and pd.products_id = p2c.products_id
                               ".$group_check."
                               and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$fsk_lock."
                               and p2c.categories_id = '" . $current_category_id . "'".$sorting;
      }
    }
    // optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($_GET['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' order by cd.categories_name";
	  } else {
        $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
	  $filterlist = $db->Execute($filterlist_sql);

	    if ($filterlist->RecordCount() > 1 ) {
        $manufacturer_dropdown= twe_draw_form('filter', FILENAME_DEFAULT, 'GET') .'&nbsp;';
        if (isset($_GET['manufacturers_id'])) {
          $manufacturer_dropdown .= twe_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
          $options = array(array('text' => TEXT_ALL_CATEGORIES));
        } else {
          $manufacturer_dropdown .= twe_draw_hidden_field('cPath', $cPath);
          $options = array(array('text' => TEXT_ALL_MANUFACTURERS));
        }
        $manufacturer_dropdown .= twe_draw_hidden_field('sort', $_GET['sort']);
		
        while (!$filterlist->EOF) {
          $options[] = array('id' => $filterlist->fields['id'], 'text' => $filterlist->fields['name']);
		$filterlist->MoveNext();	  
        }
        $manufacturer_dropdown .= twe_draw_pull_down_menu('filter_id', $options, $_GET['filter_id'], 'onchange="this.form.submit()"');
        $manufacturer_dropdown .= '</form>' . "\n";
      }
    }

   
 include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);

  } else { // default page

  include(DIR_WS_INCLUDES . FILENAME_CENTER_MODULES);

  $default_smarty->assign('language', $_SESSION['language']);

      // set cache ID
  if (USE_CACHE=='false') {
  $default_smarty->caching = 0;
  $main_content= $default_smarty->fetch(CURRENT_TEMPLATE.'/module/main_content.html');
  } else {
  $default_smarty->caching = 1;
  $default_smarty->cache_lifetime=CACHE_LIFETIME;
  $default_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'].$_SESSION['customer_id'];
  $main_content= $default_smarty->fetch(CURRENT_TEMPLATE.'/module/main_content.html',$cache_id);
  }
    $smarty->assign('main_content',$main_content);
  }
  }
?>