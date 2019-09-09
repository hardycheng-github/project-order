<?php
/* --------------------------------------------------------------
   $Id: column_left.php,v 1.13 2004/08/14  oldpa Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(column_left.php,v 1.15 2002/01/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (column_left.php,v 1.25 2003/08/19); www.nextcommerce.org
   (c) 2003	 xtcommerce (column_left.php,v 1.25 2004/04/14); www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  $admin_access_query = "select * from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . $_SESSION['customer_id'] . "'";
  $admin_access = $db->Execute($admin_access_query);  
  if($_SESSION['customers_status']['customers_status_id'] == '0') {
  if(CONFIGURATION_NOTE == 'true')require(DIR_WS_BOXES . 'configuration.php');
  if(CATEGORIES_NOTE == 'true')require(DIR_WS_BOXES . 'catalog.php');
  if(MODULES_NOTE == 'true')require(DIR_WS_BOXES . 'modules.php');
  if(CUSTOMERS_NOTE == 'true')require(DIR_WS_BOXES . 'customers.php');
  if(LOCALIZATION_NOTE == 'true')require(DIR_WS_BOXES . 'localization.php');
  if(REPORTS_NOTE == 'true')require(DIR_WS_BOXES . 'reports.php');
  if(TOOLS_NOTE == 'true')require(DIR_WS_BOXES . 'tools.php');
  if (ACTIVATE_GIFT_SYSTEM=='true'&& GV_ADMIN_NOTE == 'true') require(DIR_WS_BOXES . 'gv_queue.php');
  }
 ?>