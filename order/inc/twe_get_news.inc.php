<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_parse_category_path.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_parse_category_path.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
 // include needed function

function twe_remove_news_category($category_id) {
global $db;
    $category_image_query = "select categories_image from " . TABLE_NEWS_CATEGORIES . " where categories_id = '" . twe_db_input($category_id) . "'";
    $category_image = $db->Execute($category_image_query);

    $duplicate_image_query = "select count(*) as total from " . TABLE_NEWS_CATEGORIES . " where categories_image = '" . twe_db_input($category_image->fields['categories_image']) . "'";
    $duplicate_image = $db->Execute($duplicate_image_query);

    if ($duplicate_image->fields['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES .'/news_categories/'. $category_image->fields['categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES .'/news_categories/'. $category_image->fields['categories_image']);
      }
    }

    $db->Execute("delete from " . TABLE_NEWS_CATEGORIES . " where categories_id = '" . twe_db_input($category_id) . "'");
    $db->Execute("delete from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . twe_db_input($category_id) . "'");
    $db->Execute("delete from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . twe_db_input($category_id) . "'");

    }

function twe_remove_news_product($product_id) {
global $db;
    $product_image = $db->Execute("select products_image from " . TABLE_NEWS_PRODUCTS . " where products_id = '" . twe_db_input($product_id) . "'");

    $duplicate_image = $db->Execute("select count(*) as total from " . TABLE_NEWS_PRODUCTS . " where products_image = '" . twe_db_input($product_image->fields['products_image']) . "'");

    if ($duplicate_image->fields['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES .'/news_product/'. $product_image->fields['products_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES .'/news_product/'. $product_image->fields['products_image']);
      }
    }

    $db->Execute("delete from " . TABLE_NEWS_PRODUCTS . " where products_id = '" . twe_db_input($product_id) . "'");
	$db->Execute("delete from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . twe_db_input($product_id) . "'");
    $db->Execute("delete from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . twe_db_input($product_id) . "'");

    $customers_status_array=twe_get_customers_statuses();
    for ($i=0,$n=sizeof($customers_status_array);$i<$n;$i++) {
     $db->Execute("delete from personal_offers_by_customers_status_" . $i . " where products_id = '" . twe_db_input($product_id) . "'");
    }    
 }

function twe_set_news_product_status($products_id, $status) {
global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_NEWS_PRODUCTS . " set products_status = '1', products_last_modified = now() where products_id = '" . $products_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_NEWS_PRODUCTS . " set products_status = '0', products_last_modified = now() where products_id = '" . $products_id . "'");
    } else {
      return -1;
    }
  }

function twe_set_news_categories_rekursiv($categories_id,$status) {
global $db;
           // get products in categorie
           $products=$db->Execute("SELECT products_id FROM ".TABLE_NEWS_PRODUCTS_TO_CATEGORIES." where categories_id='".$categories_id."'");
           while (!$products->EOF) {
           $db->Execute("UPDATE ".TABLE_NEWS_PRODUCTS." SET products_status='".$status."' where products_id='".$products->fields['products_id']."'");
           $products->MoveNext();
		   }
           // set status of categorie
           $db->Execute("update " . TABLE_NEWS_CATEGORIES . " set categories_status = '".$status."' where categories_id = '" . $categories_id . "'");
           // look for deeper categories and go rekursiv
           $categories=$db->Execute("SELECT categories_id FROM ".TABLE_NEWS_CATEGORIES." where parent_id='".$categories_id."'");
           while (!$categories->EOF) {
           twe_set_categories_rekursiv($categories->fields['categories_id'],$status);
           $categories->MoveNext();
		   }

  }

function twe_get_news_products_name($product_id, $language_id = 0) {
global $db;
    if ($language_id == 0) $language_id = $_SESSION['languages_id'];
    $product_query = "select products_name from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_name'];
  }

function twe_news_product_info_image($image, $alt, $width = '', $height = '') {
global $db;
    if ( ($image) && (file_exists(DIR_FS_CATALOG_IMAGES.'news_product/' . $image)) ) {
      $image = twe_image(DIR_WS_CATALOG_IMAGES .'news_product/'. $image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }  

function twe_childs_in_news_category_count($categories_id) {
global $db;
    $categories_count = 0;

    $categories = $db->Execute("select categories_id from " . TABLE_NEWS_CATEGORIES . " where parent_id = '" . $categories_id . "'");
    while (!$categories->EOF) {
      $categories_count++;
      $categories_count += twe_childs_in_news_category_count($categories->fields['categories_id']);
    $categories->MoveNext();
	}

    return $categories_count;
  }
  
function twe_products_in_news_category_count($categories_id, $include_deactivated = false) {
global $db;
    $products_count = 0;

    if ($include_deactivated) {
      $products_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $categories_id . "'";
    } else {
      $products_query = "select count(*) as total from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . $categories_id . "'";
    }

    $products = $db->Execute($products_query);

    $products_count += $products->fields['total'];

    $childs_query = "select categories_id from " . TABLE_NEWS_CATEGORIES . " where parent_id = '" . $categories_id . "'";
    $childs = $db->Execute($childs_query);
	if ($childs->RecordCount()>0) {
      while (!$childs->EOF) {
        $products_count += twe_products_in_news_category_count($childs->fields['categories_id'], $include_deactivated);
      $childs->MoveNext();
	  }
    }

    return $products_count;
  }
  
function twe_get_news_path($current_category_id = '') {
global $db;
    global $cPath_array;

    if ($current_category_id == '') {
      $cPath_new = implode('_', $cPath_array);
    } else {
      if (sizeof($cPath_array) == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = "select parent_id from " . TABLE_NEWS_CATEGORIES . " where categories_id = '" . $cPath_array[(sizeof($cPath_array)-1)] . "'";
        $last_category = $db->Execute($last_category_query);
        $current_category_query = "select parent_id from " . TABLE_NEWS_CATEGORIES . " where categories_id = '" . $current_category_id . "'";
        $current_category = $db->Execute($current_category_query);
        if ($last_category->fields['parent_id'] == $current_category->fields['parent_id']) {
          for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;
        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    }

    return 'cPath=' . $cPath_new;
  }

function twe_get_news_products_url($product_id, $language_id) {
global $db;
    $product_query = "select products_url from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_url'];
  }
function twe_get_news_products_description($product_id, $language_id) {
global $db;
    $product_query = "select products_description from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_description'];
  }
function twe_get_news_products_short_description($product_id, $language_id) {
global $db;
    $product_query = "select products_short_description from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_short_description'];
  }
function twe_get_news_products_meta_title($product_id, $language_id) {
global $db;
    $product_query = "select products_meta_title from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_meta_title'];
  }
 function twe_get_news_products_meta_description($product_id, $language_id) {
 global $db;
    $product_query = "select products_meta_description from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_meta_description'];
  }
function twe_get_news_products_meta_keywords($product_id, $language_id) {
global $db;
    $product_query = "select products_meta_keywords from " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $language_id . "'";
    $product = $db->Execute($product_query);

    return $product->fields['products_meta_keywords'];
  }
function twe_get_news_categories_name($category_id, $language_id) {
global $db;
    $category_query = "select categories_name from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);

    return $category->fields['categories_name'];
  }
function twe_generate_news_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
global $db;
    if (!is_array($categories_array)) $categories_array = array();

    if ($from == 'product') {
      $categories = $db->Execute("select categories_id from " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $id . "'");
      while (!$categories->EOF) {
        if ($categories->fields['categories_id'] == '0') {
          $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $category_query = "select cd.categories_name, c.parent_id from " . TABLE_NEWS_CATEGORIES . " c, " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $categories->fields['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "'";
          $category = $db->Execute($category_query);
          $categories_array[$index][] = array('id' => $categories->fields['categories_id'], 'text' => $category->fields['categories_name']);
          if ( (twe_not_null($category->fields['parent_id'])) && ($category->fields['parent_id'] != '0') ) $categories_array = twe_generate_news_category_path($category->fields['parent_id'], 'category', $categories_array, $index);
          $categories_array[$index] = twe_array_reverse($categories_array[$index]);
        }
        $index++;
		$categories->MoveNext();
      }
    } elseif ($from == 'category') {
      $category_query = "select cd.categories_name, c.parent_id from " . TABLE_NEWS_CATEGORIES . " c, " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "'";
      $category = $db->Execute($category_query);
      $categories_array[$index][] = array('id' => $id, 'text' => $category->fields['categories_name']);
      if ( (twe_not_null($category->fields['parent_id'])) && ($category->fields['parent_id'] != '0') ) $categories_array = twe_generate_news_category_path($category->fields['parent_id'], 'category', $categories_array, $index);
    }

    return $categories_array;
  }
function twe_get_news_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
global $db;
    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = "select cd.categories_name from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . $_SESSION['languages_id'] . "' and cd.categories_id = '" . $parent_id . "'";
      $category = $db->Execute($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category->fields['categories_name']);
    }

    $categories = $db->Execute("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_NEWS_CATEGORIES . " c, " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "' and c.parent_id = '" . $parent_id . "' order by c.sort_order, cd.categories_name");
    while (!$categories->EOF) {
      if ($exclude != $categories->fields['categories_id']) $category_tree_array[] = array('id' => $categories->fields['categories_id'], 'text' => $spacing . $categories->fields['categories_name']);
      $category_tree_array = twe_get_news_category_tree($categories->fields['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
      $categories->MoveNext();
	}
    return $category_tree_array;
  }

  
function twe_output_generated_news_category_path($id, $from = 'category') {
global $db;
    $calculated_category_path_string = '';
    $calculated_category_path = twe_generate_news_category_path($id, $from);
    for ($i = 0, $n = sizeof($calculated_category_path); $i < $n; $i++) {
      for ($j = 0, $k = sizeof($calculated_category_path[$i]); $j < $k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }

 function twe_get_news_categories_heading_title($category_id, $language_id) {
 global $db;
    $category_query = "select categories_heading_title from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    return $category->fields['categories_heading_title'];
  }

  function twe_get_news_categories_description($category_id, $language_id) {
  global $db;
    $category_query = "select categories_description from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_description'];
  }

  function twe_get_news_categories_meta_title($category_id, $language_id) {
  global $db;
    $category_query = "select categories_meta_title from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_meta_title'];
  }

  function twe_get_news_categories_meta_description($category_id, $language_id) {
  global $db;
    $category_query ="select categories_meta_description from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_meta_description'];
  }

  function twe_get_news_categories_meta_keywords($category_id, $language_id) {
  global $db;
    $category_query = "select categories_meta_keywords from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'";
    $category = $db->Execute($category_query);
    
    return $category->fields['categories_meta_keywords'];
  }
  
   function twe_info_image_news($image, $alt, $width = '', $height = '') {
   global $db;
    if ( ($image) && (file_exists(DIR_FS_CATALOG_IMAGES .'news_categories/'. $image)) ) {
      $image = twe_image(DIR_WS_CATALOG_IMAGES .'news_categories/'. $image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }

?>