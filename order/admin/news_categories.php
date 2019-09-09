<?php
/* --------------------------------------------------------------
   $Id: news_categories.php,v 1.45 2005/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.140 2003/03/24); www.oscommerce.com
   (c) 2003  nextcommerce (categories.php,v 1.37 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contribution:
   Enable_Disable_Categories 1.3               Autor: Mikel Williams | mikel@ladykatcostumes.com
   New Attribute Manager v4b                   Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   Category Descriptions (Version: 1.5 MS2)    Original Author:   Brian Lowe <blowe@wpcusrgrp.org> | Editor: Lord Illicious <shaolin-venoms@illicious.net>
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application_top.php');
  include ('includes/classes/image_manipulator.php');
  require_once(DIR_FS_INC . 'twe_get_news.inc.php');
  require_once(DIR_FS_INC . 'twe_get_more_images.inc.php'); 

  if ($_GET['function']) {
    switch ($_GET['function']) {
      case 'delete':
        $db->Execute("DELETE FROM personal_offers_by_customers_status_" . (int)$_GET['statusID'] . " WHERE products_id = '" . (int)$_GET['pID'] . "' AND quantity = '" . (int)$_GET['quantity'] . "'");
    break;
    }
    twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&action=new_product&pID=' . (int)$_GET['pID']));
  }     
  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if ($_GET['pID']) {
            twe_set_news_product_status($_GET['pID'], $_GET['flag']);
          }
          if ($_GET['cID']) {
            twe_set_news_categories_rekursiv($_GET['cID'], $_GET['flag']);
          }
        }

        twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $_GET['cPath']));
        break;

      case 'new_category':
      case 'edit_category':
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
        $_GET['action']=$_GET['action'] . '_ACD';
        break;
        
      case 'insert_category':
      case 'update_category':
        if (($_POST['edit_x']) || ($_POST['edit_y'])) {
          $_GET['action'] = 'edit_category_ACD';
        } else {
        $categories_id = twe_db_prepare_input($_POST['categories_id']);
        if ($categories_id == '') {
        $categories_id = twe_db_prepare_input($_GET['cID']);
        }
        $sort_order = twe_db_prepare_input($_POST['sort_order']);
        $categories_status = twe_db_prepare_input($_POST['categories_status']);

        // set allowed c.groups
        $group_ids='';
        if(isset($_POST['groups'])) foreach($_POST['groups'] as $b){
        $group_ids .= 'c_'.$b."_group ,";
        }
        $customers_statuses_array=twe_get_customers_statuses();
        if (strstr($group_ids,'c_all_group')) {
        $group_ids='c_all_group,';
         for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
            $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
         }
        }


        $sql_data_array = array( 'sort_order' => $sort_order,
                                 'group_ids'=>$group_ids,
                                 'categories_status' => $categories_status,
                                 'products_sorting' => twe_db_prepare_input($_POST['products_sorting']),
                                 'products_sorting2' => twe_db_prepare_input($_POST['products_sorting2']),
                                 'categories_template'=>twe_db_prepare_input($_POST['categorie_template']),
                                 'listing_template'=>twe_db_prepare_input($_POST['listing_template']));

        if ($_GET['action'] == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');
          $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
          twe_db_perform(TABLE_NEWS_CATEGORIES, $sql_data_array);
          $categories_id = $db->Insert_ID();
        } elseif ($_GET['action'] == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');
          $sql_data_array = twe_array_merge($sql_data_array, $update_sql_data);
          twe_db_perform(TABLE_NEWS_CATEGORIES, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\'');
        }
        twe_set_groups($categories_id,$group_ids);
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $categories_name_array = $_POST['categories_name'];
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('categories_name' => twe_db_prepare_input($categories_name_array[$language_id]));
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('categories_name' => twe_db_prepare_input($_POST['categories_name'][$language_id]),
                                      'categories_heading_title' => twe_db_prepare_input($_POST['categories_heading_title'][$language_id]),
                                      'categories_description' => twe_db_prepare_input($_POST['categories_description'][$language_id]),
                                      'categories_meta_title' => twe_db_prepare_input($_POST['categories_meta_title'][$language_id]),
                                      'categories_meta_description' => twe_db_prepare_input($_POST['categories_meta_description'][$language_id]),
                                      'categories_meta_keywords' => twe_db_prepare_input($_POST['categories_meta_keywords'][$language_id]));
            }
         
          if ($_GET['action'] == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);
            $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
            twe_db_perform(TABLE_NEWS_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($_GET['action'] == 'update_category') {
            twe_db_perform(TABLE_NEWS_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\' and language_id = \'' . $languages[$i]['id'] . '\'');
          }
        }
           
            if ($categories_image =twe_try_upload('categories_image', DIR_FS_CATALOG_IMAGES.'news_categories/','777','')) {
			$categories_image_name = twe_db_input($categories_image->filename);
			}else{
			$categories_image_name = twe_db_prepare_input($_POST['categories_previous_image']);
			}
            $db->Execute("update " . TABLE_NEWS_CATEGORIES . " set categories_image = '" . $categories_image_name . "' where categories_id = '" . (int)$categories_id . "'");
            //}
          
          twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&cID=' . $categories_id));
        }
        break;

        
      case 'delete_category_confirm':
        if ($_POST['categories_id']) {
          $categories_id = twe_db_prepare_input($_POST['categories_id']);

          $categories = twe_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
            $product_ids = $db->Execute("select products_id from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $categories[$i]['id'] . "'");
            while (!$product_ids->EOF) {
              $products[$product_ids->fields['products_id']]['categories'][] = $categories[$i]['id'];
            $product_ids->MoveNext();
			}
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';
            for ($i = 0, $n = sizeof($value['categories']); $i < $n; $i++) {
              $category_ids .= '\'' . $value['categories'][$i] . '\', ';
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $key . "' and categories_id not in (" . $category_ids . ")";
            $check = $db->Execute($check_query);
            if ($check->fields['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

          // Removing categories can be a lengthy process
          @twe_set_time_limit(0);
          for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
            twe_remove_news_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            twe_remove_news_product($key);
          }
        }

        twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if ( ($_POST['products_id']) && (is_array($_POST['product_categories'])) ) {
          $product_id = twe_db_prepare_input($_POST['products_id']);
          $product_categories = $_POST['product_categories'];

          for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
            $db->Execute("delete from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($product_id) . "' and categories_id = '" . twe_db_input($product_categories[$i]) . "'");
          }

          $product_categories_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($product_id) . "'";
          $product_categories = $db->Execute($product_categories_query);

          if ($product_categories->fields['total'] == '0') {
            twe_remove_news_product($product_id);
          }
        }

        twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if ( ($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id']) ) {
          $categories_id = twe_db_prepare_input($_POST['categories_id']);
          $new_parent_id = twe_db_prepare_input($_POST['move_to_category_id']);
          $db->Execute("update " . TABLE_NEWS_CATEGORIES . " set parent_id = '" . twe_db_input($new_parent_id) . "', last_modified = now() where categories_id = '" . twe_db_input($categories_id) . "'");
        }

        twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        break;
      case 'move_product_confirm':
        $products_id = twe_db_prepare_input($_POST['products_id']);
        $new_parent_id = twe_db_prepare_input($_POST['move_to_category_id']);

        $duplicate_check_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($products_id) . "' and categories_id = '" . twe_db_input($new_parent_id) . "'";
        $duplicate_check = $db->Execute($duplicate_check_query);
        if ($duplicate_check->fields['total'] < 1) $db->Execute("update " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . twe_db_input($new_parent_id) . "' where products_id = '" . twe_db_input($products_id) . "' and categories_id = '" . $current_category_id . "'");

        twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
       

        if ( ($_POST['edit_x']) || ($_POST['edit_y']) ) {
          $_GET['action'] = 'new_product';
        } else {
          $products_id = twe_db_prepare_input($_GET['pID']);
          $products_date_available = twe_db_prepare_input($_POST['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

                  // set allowed c.groups
        $group_ids='';
        if(isset($_POST['groups'])) foreach($_POST['groups'] as $b){
        $group_ids .= 'c_'.$b."_group ,";
        }
        $customers_statuses_array=twe_get_customers_statuses();
        if (strstr($group_ids,'c_all_group')) {
        $group_ids='c_all_group,';
         for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
            $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
         }
        }

          $sql_data_array = array(
                                  'products_model' => twe_db_prepare_input($_POST['products_model']),
                                  'products_sort' => twe_db_prepare_input($_POST['products_sort']),
                                  'group_ids'=>$group_ids,
                                  'products_discount_allowed' => twe_db_prepare_input($_POST['products_discount_allowed']),
                                  'products_date_available' => $products_date_available,
                                  'products_status' => twe_db_prepare_input($_POST['products_status']),
                                  'product_template' => twe_db_prepare_input($_POST['info_template']),
                                  'manufacturers_id' => twe_db_prepare_input($_POST['manufacturers_id']),
                                  'products_fsk18' => twe_db_prepare_input($_POST['fsk18']));

       

          if ($_GET['action'] == 'insert_product') {
            $insert_sql_data = array('products_date_added' => 'now()');
            $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
            twe_db_perform(TABLE_NEWS_PRODUCTS, $sql_data_array);
            $products_id = $db->Insert_ID();
            $db->Execute("insert into " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $products_id . "', '" . $current_category_id . "')");
          } elseif ($_GET['action'] == 'update_product') {
            $update_sql_data = array('products_last_modified' => 'now()');
            $sql_data_array = twe_array_merge($sql_data_array, $update_sql_data);
            twe_db_perform(TABLE_NEWS_PRODUCTS, $sql_data_array, 'update', 'products_id = \'' . twe_db_input($products_id) . '\'');          
          }
         
		 if ($products_image =twe_try_upload('products_image', DIR_FS_CATALOG_IMAGES.'news_product/','777','')) {
			$products_image_name = twe_db_input($products_image->filename);
			}else{
			$products_image_name = twe_db_prepare_input($_POST['products_previous_image']);
			}

            $db->Execute("update " . TABLE_NEWS_PRODUCTS . " set products_image = '" . $products_image_name . "' where products_id = '" . twe_db_input($products_id) . "'");

          $languages = twe_get_languages(); 
              	               
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
            $sql_data_array = array('products_name' => twe_db_prepare_input($_POST['products_name'][$language_id]),
                                    'products_description' => twe_db_prepare_input($_POST['products_description_'.$language_id]),
                                    'products_short_description' => twe_db_prepare_input($_POST['products_short_description_'.$language_id]),
                                    'products_url' => twe_db_prepare_input($_POST['products_url'][$language_id]),
                                    'products_meta_title' => twe_db_prepare_input($_POST['products_meta_title'][$language_id]),
                                    'products_meta_description' => twe_db_prepare_input($_POST['products_meta_description'][$language_id]),
                                    'products_meta_keywords' => twe_db_prepare_input($_POST['products_meta_keywords'][$language_id]));

            if ($_GET['action'] == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);
              $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);

              twe_db_perform(TABLE_NEWS_PRODUCTS_DESCRIPTION, $sql_data_array);
            } elseif ($_GET['action'] == 'update_product') {
              twe_db_perform(TABLE_NEWS_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = \'' . twe_db_input($products_id) . '\' and language_id = \'' . $language_id . '\'');
            }
          }

          twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
        break;
      case 'copy_to_confirm':

        if(isset($_POST['cat_ids']) && $_POST['copy_as'] == 'link') {
        $products_id = twe_db_prepare_input($_POST['products_id']);

        foreach($_POST['cat_ids'] as $key){
              $check_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "' and categories_id = '" . $key . "'";
              $check = $db->Execute($check_query);
              if ($check->fields['total'] < '1') {
                $db->Execute("insert into " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $products_id . "', '" . $key . "')");
              } else  {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }

        }
        break;
        }


        if ( (twe_not_null($_POST['products_id'])) && (twe_not_null($_POST['categories_id'])) ) {
          $products_id = twe_db_prepare_input($_POST['products_id']);

          $categories_id = twe_db_prepare_input($_POST['categories_id']);
          if(isset($_POST['cat_ids'])) {
          $cat_ids=$_POST['cat_ids'];
          } else {
          $cat_ids=array('0'=>$categories_id);
          }

          foreach($cat_ids as $key) {
          $categories_id=$key;

          if ($_POST['copy_as'] == 'link') {
            if ($_POST['categories_id'] != $current_category_id) {
              $check_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($products_id) . "' and categories_id = '" . twe_db_input($categories_id) . "'";
              $check = $db->Execute($check_query);
              if ($check->fields['total'] < '1') {
                $db->Execute("insert into " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . twe_db_input($products_id) . "', '" . twe_db_input($categories_id) . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($_POST['copy_as'] == 'duplicate') {
            $product_query = "select
                                           products_model,
                                           group_ids,
                                           products_sort,
                                           products_image,
                                           products_discount_allowed,
                                           products_date_available,
										   products_status,
                                           manufacturers_id,
                                           product_template,
                                           products_fsk18
                                           from " . TABLE_NEWS_PRODUCTS . "
                                           where products_id = '" . twe_db_input($products_id) . "'";

            $product = $db->Execute($product_query);
            $db->Execute("insert into " . TABLE_NEWS_PRODUCTS . "
                          (products_model,
                          group_ids,
                          products_sort,
                          products_image,
                          products_discount_allowed,
                          products_date_added,
                          products_date_available,
						  products_status,
                          manufacturers_id,
                          product_template,
                          products_fsk18)
                          values
                          ('" . $product->fields['products_model'] . "',
						  '" . $product->fields['group_ids'] . "',
                          '" . $product->fields['products_sort'] . "',
                          '" . $product->fields['products_image'] . "',
                          '" . $product->fields['products_discount_allowed'] . "',
                          now(),
                          '" . $product->fields['products_date_available'] . "',
						  '0',
                          '" . $product->fields['manufacturers_id'] . "',
                          '" . $product->fields['product_template'] . "',
                          '" . $product->fields['products_fsk18'] . "'
                          )");

            $dup_products_id = $db->Insert_ID();

            $description = $db->Execute("select
                                               language_id,
                                               products_name,
                                               products_description,
                                               products_short_description,
                                               products_meta_title,
                                               products_meta_description,
                                               products_meta_keywords,
                                               products_url
                                               from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . "
                                               where products_id = '" . twe_db_input($products_id) . "'");
            $old_products_id=twe_db_input($products_id);
            while (!$description->EOF) {
              $db->Execute("insert into " . TABLE_NEWS_PRODUCTS_DESCRIPTION . "
                            (products_id,
                            language_id,
                            products_name,
                            products_description,
                            products_short_description,
                            products_meta_title,
                            products_meta_description,
                            products_meta_keywords,
                            products_url,
                            products_viewed)
                            values (
                            '" . $dup_products_id . "',
                            '" . $description->fields['language_id'] . "',
                            '" . addslashes($description->fields['products_name']) . "',
                            '" . addslashes($description->fields['products_description']) . "',
                            '" . addslashes($description->fields['products_short_description']) . "',
                            '" . addslashes($description->fields['products_meta_title']) . "',
                            '" . addslashes($description->fields['products_meta_description']) . "',
                            '" . addslashes($description->fields['products_meta_keywords']) . "',
                            '" . $description->fields['products_url'] . "',
                            '0')");
           $description->MoveNext();
		    }

            $db->Execute("insert into " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $dup_products_id . "', '" . twe_db_input($categories_id) . "')");

            $products_id = $dup_products_id;


          $i = 0;
          $group_values = $db->Execute("SELECT customers_status_id FROM " . TABLE_CUSTOMERS_STATUS . " WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' AND customers_status_id != '0'");
          while (!$group_values->EOF) {
            // load data into array
            $i++;
            $group_data[$i]=array('STATUS_ID' => $group_values->fields['customers_status_id']);
          $group_values->MoveNext();
		  }
          for ($col = 0, $n = sizeof($group_data); $col < $n+1; $col++) {
            if ($group_data[$col]['STATUS_ID'] != '') {

            $copy_data=$db->Execute("SELECT
                                      quantity,
                                      personal_offer
                                      FROM personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . "
                                      WHERE products_id='".$old_products_id."'");
            while (!$copy_data->EOF) {

                $db->Execute("INSERT INTO
                personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . "
                (price_id, products_id, quantity, personal_offer)
                 VALUES ('', '" . $products_id . "', '" . $copy_data->fields['quantity']. "', '" . $copy_data->fields['personal_offer'] . "')");
          $copy_data->MoveNext();
            }
          }

          }
          }
          }

          }

        twe_redirect(twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;


    }
  }

  // check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php if (USE_SPAW=='true') {
 $query="SELECT code FROM ". TABLE_LANGUAGES ." WHERE languages_id='".$_SESSION['languages_id']."'";
 $data=$db->Execute($query);
 ?>
<script type="text/javascript">
   _editor_url = "includes/htmlarea/";
   _editor_lang = "<?php echo $data->fields['code']; ?>";
</script>
    <!-- DWD Modify -> Add: HTMLArea v3.0 !-->
    <!-- Load HTMLArea Core Files. !-->
<script type="text/javascript" src="includes/htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="includes/htmlarea/dialog.js"></script>
<script tyle="text/javascript" src="includes/htmlarea/lang/<?php echo $data->fields['code']; ?>.js"></script>


<?php } ?>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') {
  include(DIR_WS_MODULES.'news/news_new_categorie.php');
  //----- new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  } elseif ($_GET['action'] == 'new_category_preview') {
  // removed
  } elseif ($_GET['action'] == 'new_product') {
  include(DIR_WS_MODULES.'news/news_new_product.php');
  } elseif ($_GET['action'] == 'new_product_preview') {
  // preview removed
  } else {
  if (!$cPath) $cPath = '0';
  include(DIR_WS_MODULES.'news/news_categories_view.php');
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
<?php if (USE_SPAW=='true') { ?>
<script type="text/javascript">
      HTMLArea.loadPlugin("SpellChecker");
      HTMLArea.loadPlugin("TableOperations");
      HTMLArea.loadPlugin("FullPage");
      HTMLArea.loadPlugin("CharacterMap");
      HTMLArea.loadPlugin("ContextMenu");
      HTMLArea.loadPlugin("ImageManager");
HTMLArea.onload = function() {

<?php
// generate  for categories EDIT
$languages = twe_get_languages();
if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') {
for ($i=0; $i<sizeof($languages); $i++) {
?>
var editor<?php echo $languages[$i]['id']; ?> = new HTMLArea("categories_description[<?php echo $languages[$i]['id']; ?>]");
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(TableOperations);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(FullPage);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(ContextMenu);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(CharacterMap);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(ImageManager);

editor<?php echo $languages[$i]['id']; ?>.generate();

<?php

}
}
// generate editor for products
if ($_GET['action'] == 'new_product') {

for ($i=0; $i<sizeof($languages); $i++) {
?>
var editor<?php echo $languages[$i]['id']; ?> = new HTMLArea("products_description_<?php echo $languages[$i]['id']; ?>");
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(TableOperations);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(FullPage);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(ContextMenu);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(CharacterMap);
editor<?php echo $languages[$i]['id']; ?>.registerPlugin(ImageManager);
editor<?php echo $languages[$i]['id']; ?>.generate();

var editorshort<?php echo $languages[$i]['id']; ?> = new HTMLArea("products_short_description_<?php echo $languages[$i]['id']; ?>");
editorshort<?php echo $languages[$i]['id']; ?>.registerPlugin(TableOperations);
editorshort<?php echo $languages[$i]['id']; ?>.registerPlugin(FullPage);
editorshort<?php echo $languages[$i]['id']; ?>.registerPlugin(ContextMenu);
editorshort<?php echo $languages[$i]['id']; ?>.registerPlugin(CharacterMap);
editorshort<?php echo $languages[$i]['id']; ?>.registerPlugin(ImageManager);
editorshort<?php echo $languages[$i]['id']; ?>.generate();

<?php
}
}

?>

};
HTMLArea.init();
</script>
<?php } ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>