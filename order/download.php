<?php
/* -----------------------------------------------------------------------------------------
   $Id: download.php,v 1.1 2003/09/06 21:38:27 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(download.php,v 1.9 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (download.php,v 1.7 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'twe_random_name.inc.php');
  require_once(DIR_FS_INC . 'twe_unlink_temp_dir.inc.php');

  if (!isset($_SESSION['customer_id'])) die;

  // Check download.php was called with proper GET parameters
  if ((isset($_GET['order']) && !is_numeric($_GET['order'])) || (isset($_GET['Did']) && !is_numeric($_GET['Did'])) ) {
    die;
  }
  
  // Check that order_id, customer_id and filename match
  $downloads_query = "select date_format(o.date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, opd.download_count, opd.download_maxdays, opd.orders_products_filename from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.customers_id = '" . $_SESSION['customer_id'] . "' and o.orders_id = '" . (int)$_GET['order'] . "' and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_download_id = '" . (int)$_GET['Did'] . "' and opd.orders_products_filename != ''";
   $downloads = $db->Execute($downloads_query);

  if (!$downloads->RecordCount()) die;
  // MySQL 3.22 does not have INTERVAL
  list($dt_year, $dt_month, $dt_day) = explode('-', $downloads->fields['date_purchased_day']);
  $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads->fields['download_maxdays'], $dt_year);

  // Die if time expired (maxdays = 0 means no time limit)
  if (($downloads->fields['download_maxdays'] != 0) && ($download_timestamp <= time())) die;
  // Die if remaining count is <=0
  if ($downloads->fields['download_count'] <= 0) die;
  // Die if file is not there
  if (!file_exists(DIR_FS_DOWNLOAD . $downloads->fields['orders_products_filename'])) die;

  // Now decrement counter
  $db->Execute("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_count = download_count-1 where orders_products_download_id = '" . (int)$_GET['Did'] . "'");

  // Now send the file with header() magic
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-disposition: attachment; filename=" . $downloads->fields['orders_products_filename']);

  if (DOWNLOAD_BY_REDIRECT == 'true') {
    // This will work only on Unix/Linux hosts
    twe_unlink_temp_dir(DIR_FS_DOWNLOAD_PUBLIC);
    $tempdir = twe_random_name();
    umask(0000);
    mkdir(DIR_FS_DOWNLOAD_PUBLIC . $tempdir, 0777);
    symlink(DIR_FS_DOWNLOAD . $downloads->fields['orders_products_filename'], DIR_FS_DOWNLOAD_PUBLIC . $tempdir . '/' . $downloads->fields['orders_products_filename']);
    twe_redirect(DIR_WS_DOWNLOAD_PUBLIC . $tempdir . '/' . $downloads->fields['orders_products_filename']);
  } else {
    // This will work on all systems, but will need considerable resources
    // We could also loop with fread($fp, 4096) to save memory
    readfile(DIR_FS_DOWNLOAD . $downloads->fields['orders_products_filename']);
  }
?>