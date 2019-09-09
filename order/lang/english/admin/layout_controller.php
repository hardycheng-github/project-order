<?php
/* -----------------------------------------------------------------------------------------
   $Id: layout_controller.php,v 1.3 2008/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------*/

define('HEADING_TITLE', 'Column Boxes');

define('TABLE_HEADING_LAYOUT_BOX_NAME_LEFT', 'File Name');
define('TABLE_HEADING_LAYOUT_BOX_NAME_RIGHT', 'File Name');
define('TABLE_HEADING_LAYOUT_BOX_STATUS', 'LEFT/RIGHT COLUMN<br />Status');
define('TABLE_HEADING_LAYOUT_BOX_STATUS_SINGLE', 'SINGLE COLUMN<br />Status');
define('TABLE_HEADING_LAYOUT_BOX_LOCATION', 'LEFT or RIGHT<br />COLUMN');
define('TABLE_HEADING_LAYOUT_BOX_SORT_ORDER', 'LEFT/RIGHT COLUMN<br />Sort Order');
define('TABLE_HEADING_LAYOUT_BOX_SORT_ORDER_SINGLE', 'SINGLE COLUMN<br />Sort Order');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_LAYOUT_BOX','Selected Box: ');
define('TEXT_INFO_LAYOUT_BOX_NAME', 'Box Name:');
define('TEXT_INFO_LAYOUT_BOX_LOCATION','Location: (Single Column ignores this setting)');
define('TEXT_INFO_LAYOUT_BOX_STATUS', 'Left/Right Column Status: ');
define('TEXT_INFO_LAYOUT_BOX_STATUS_SINGLE', 'Single Column Status: ');
define('TEXT_INFO_LAYOUT_BOX_STATUS_INFO','ON= 1 OFF=0');
define('TEXT_INFO_LAYOUT_BOX_SORT_ORDER', 'Left/Right Column Sort Order:');
define('TEXT_INFO_LAYOUT_BOX_SORT_ORDER_SINGLE', 'Single Column Sort Order:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new box with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this box?');
define('TEXT_INFO_HEADING_NEW_BOX', 'New Box');
define('TEXT_INFO_HEADING_EDIT_BOX', 'Edit Box');
define('TEXT_INFO_HEADING_DELETE_BOX', 'Delete Box');
define('TEXT_INFO_DELETE_MISSING_LAYOUT_BOX','Delete missing box from Template listing: ');
define('TEXT_INFO_DELETE_MISSING_LAYOUT_BOX_NOTE','NOTE: This does not remove files and you can re-add the box at anytime by adding it to the correct directory.<br /><br /><strong>Delete box name: </strong>');
define('TEXT_INFO_RESET_TEMPLATE_SORT_ORDER','Reset All Box Sort Order to match DEFAULT Sort Order for Template: ');
define('TEXT_INFO_RESET_TEMPLATE_SORT_ORDER_NOTE','This does not remove any of the boxes. It will only reset the current sort order');
define('TEXT_INFO_BOX_DETAILS','Box Details: ');

////////////////

define('HEADING_TITLE_LAYOUT_TEMPLATE', 'Site Template Layout');

define('TABLE_HEADING_LAYOUT_TITLE', 'Title');
define('TABLE_HEADING_LAYOUT_VALUE', 'Value');
define('TABLE_HEADING_ACTION', 'Action');


define('TEXT_MODULE_DIRECTORY', 'Site Layout Directory:');
define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');

// layout box text in includes/boxes/layout.php
define('BOX_HEADING_LAYOUT', 'Layout');
define('BOX_LAYOUT_COLUMNS', 'Column Controller');

// file exists
define('TEXT_GOOD_BOX',' ');
define('TEXT_BAD_BOX','<font color="ff0000"><b>MISSING</b></font><br />');


// Success message
define('SUCCESS_BOX_DELETED','Successfully removed from the template of the box: ');
define('SUCCESS_BOX_RESET','Successfully Reset all box settings to the Default settings for Template: ');
define('SUCCESS_BOX_UPDATED','Successfully Update settings for box: ');
define('SUCCESS_LOGO_CHANGE','Successfully Update');

define('TEXT_ON',' ON ');
define('TEXT_OFF',' OFF ');
define('TEXT_LEFT',' LEFT ');
define('TEXT_RIGHT',' RIGHT ');
define('TEXT_CHANGE_LOGO', 'Change Logo:');
define('BOXES_PATH', 'Boxes Path:');
define('BOXES_FOUND', 'New boxes found:');
define('TEMPLATE_FOUND', 'New template found:');
define('LEFT_WIDTH', 'Left Column width:');
define('RIGHT_WIDTH', 'Right Column width:');
define('CENTER_WIDTH', 'Center Column width:');
define('WIDTH','Template width:');
define('TEXT_CENTER_LEFT', 'Center left');
define('TEXT_CENTER_RIGHT', 'Center right');
define('TEXT_CENTER', 'Center');
define('MODULES_PATH', 'Center modules path');
?>