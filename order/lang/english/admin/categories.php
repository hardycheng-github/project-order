<?php
/* --------------------------------------------------------------
   $Id: categories.php,v 1.7 2004/04/15 18:22:16 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.22 2002/08/17); www.oscommerce.com
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License
   --------------------------------------------------------------*/
define('TEXT_EDIT_STATUS', 'Status');
define('HEADING_TITLE', 'Categories / Products');
define('HEADING_TITLE_SEARCH', 'Search: ');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories / Products');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_STOCK','Stock Warning');

define('TEXT_WARN_MAIN','Main');
define('TEXT_WARN_ATTRIBUTE','Attribute');
define('TEXT_NEW_PRODUCT', 'New Product in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_SUBCATEGORIES', 'Subkategories:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tax Class:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED_INFO', 'Max. allowed Discount:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in <br>&nbsp;<br><b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank"><u>webpage</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');
define('TEXT_CHOOSE_INFO_TEMPLATE', 'Product-Info Template:');
define('TEXT_CHOOSE_OPTIONS_TEMPLATE', 'Product-Optionen Template:');
define('TEXT_SELECT', 'Please select:');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_CATEGORIES_NAME_SIZE', 'Category Font Size:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Category Heading:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Category Description:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');

define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');

define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this product to');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Categories:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Category');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Category');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Delete Product');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move Product');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');

define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this product?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)Categories still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new category.');
define('TEXT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');

define('TEXT_META_TITLE', 'Meta Title:');
define('TEXT_META_DESCRIPTION', 'Meta Description:');
define('TEXT_META_KEYWORDS', 'Meta Keywords:');

define('TEXT_SORT_ORDER', 'Sort Order:');

define('TEXT_PRODUCTS_STATUS', 'Products Status:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');
define('TEXT_PRODUCT_AVAILABLE', 'In Stock');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Out of Stock');
define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Products Name:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_MODEL', 'Products Model:');
define('TEXT_PRODUCTS_IMAGE', 'Products Image:');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Products Price:');
define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');

define('EMPTY_CATEGORY', 'Empty Category');

define('TEXT_HOW_TO_COPY', 'Copy Method:');
define('TEXT_COPY_AS_LINK', 'Link product');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate product');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same directory.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);

define('TEXT_PRODUCTS_DISCOUNT_ALLOWED','Discount allowed:');
define('HEADING_PRICES_OPTIONS','<b>Price options</b>');
define('HEADING_PRODUCT_OPTIONS','<b>Product options</b>');
define('TEXT_PRODUCTS_WEIGHT_INFO','<small>(kg)</small>');
define('TEXT_PRODUCTS_SHORT_DESCRIPTION','Shortdescription:');
define('TXT_STK','Stk: ');
define('TXT_PRICE','a :');
define('TXT_NETTO','Net price: ');
define('TEXT_NETTO','Net: ');
define('TXT_STAFFELPREIS','Graduated Price');

define('HEADING_PRODUCTS_MEDIA','<b>Products Media</b>');
define('TABLE_HEADING_PRICE','Price');

define('TEXT_CHOOSE_INFO_TEMPLATE','Productdetail Template');
define('TEXT_SELECT','--Select--');
define('TEXT_CHOOSE_OPTIONS_TEMPLATE','Productoptions Template');
define('SAVE_ENTRY','Save ?');

define('TEXT_FSK18','FSK 18:');
define('TEXT_CHOOSE_INFO_TEMPLATE_CATEGORIE','Template for Category Listing');
define('TEXT_CHOOSE_INFO_TEMPLATE_LISTING','Template for Product Listing');
define('TEXT_PRODUCTS_SORT','Sorting:');
define('TEXT_EDIT_PRODUCT_SORT_ORDER','Product Sorting');
define('TXT_PRICES','Price');
define('TXT_NAME','Productname');
define('TXT_ORDERED','Products ordered');
define('TXT_SORT','Sorting');
define('TXT_WEIGHT','Weight');
define('TXT_QTY','On Stock');

define('TEXT_MULTICOPY','Multiple');
define('TEXT_MULTICOPY_DESC','Copy/link product into following categories (if 1 selected, Single settings are ignored)');
define('TEXT_SINGLECOPY','Single');
define('TEXT_SINGLECOPY_DESC','Copy/link product into following categorie');
define('TEXT_FEATURED','Featured');

?>