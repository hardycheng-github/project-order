<?php
/* --------------------------------------------------------------
   $Id: backup.php,v 1.4 2005/04/29 17:05:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(backup.php,v 1.57 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (backup.php,v 1.11 2003/08/2); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (twe_not_null($action)) {
    switch ($action) {
	  case 'backup_file':
	  ini_set('memory_limit', '-1');
      ini_set('register_globals', 0);
      ini_set('max_execution_time', 0);
      //=============================================================
      $tar_file = $_SERVER['HTTP_HOST'].'_'.date("d-m-Y").'.tar';
      $web_root = $_SERVER['DOCUMENT_ROOT'].DIR_WS_CATALOG;
      chdir("$web_root");
      //=============================================================      
      Class Tar_Archive {
        var $tar_file;
        var $fp;
        function Tar_Archive($tar_file) {
          $this->tar_file = $tar_file;
          $this->fp = fopen($this->tar_file, "wb");
          $tree = $this->build_tree();
          $this->process_tree($tree);
          fputs($this->fp, pack("a512", ""));
          fclose($this->fp);
          ignore_user_abort(true);
          header("Pragma: public");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Cache-Control: private",false);
          header("Content-Type: application/octet-stream");
          header("Content-Disposition: attachment; filename=".basename($tar_file).";");
          header("Content-Transfer-Encoding: binary");
          header("Content-Length: ".filesize($tar_file));
          readfile($tar_file);
          unlink($tar_file);
        }
        function build_tree($dir = '.') {
          $output = array();
          $handle = opendir($dir);
          while(false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..') {
              $path = $dir.'/'.$readdir;
              if (!is_link($path)) {
                if (is_file($path)) {
                  $output[] = substr($path, 2, strlen($path));
                } elseif (is_dir($path)) {
                  global $excludir;
                  if (!empty($excludir)) {
                    $pos = strpos($path, $excludir);
                    if (($pos !== false) && (is_dir($excludir))) {
                      $output[] = "";
                    } else {
                      $output[] = substr($path, 2, strlen($path)).'/';
                      $output = array_merge($output, $this->build_tree($path));
                    }
                  } else {
                    $output[] = substr($path, 2, strlen($path)).'/';
                    $output = array_merge($output, $this->build_tree($path));
                  }  
                }
              }
            }
          }
          closedir($handle);
          return $output;
        }
        function process_tree($tree) {
          foreach($tree as $pathfile) {
            if (substr($pathfile, -1, 1) == '/') {
              fputs($this->fp, $this->build_header($pathfile));
            } elseif ($pathfile != $this->tar_file) {
              $filesize = filesize($pathfile);
              $block_len = 512*ceil($filesize/512)-$filesize;
              fputs($this->fp, $this->build_header($pathfile));
              fputs($this->fp, file_get_contents($pathfile));
              fputs($this->fp, pack("a".$block_len, ""));
            }
          }
          return true;
        }
        function build_header($pathfile) {
          if (strlen($pathfile) > 99) die('Error');
            $info = stat($pathfile);
            if (is_dir($pathfile)) $info[7] = 0;
              $header = pack("a100a8a8a8a12A12a8a1a100a255", $pathfile, sprintf("%6s ", decoct($info[2])), sprintf("%6s ", decoct($info[4])), sprintf("%6s ", decoct($info[5])), sprintf("%11s ",decoct($info[7])), sprintf("%11s", decoct($info[9])), sprintf("%8s", " "), (is_dir($pathfile) ? "5" : "0"), "", "");
              clearstatcache();
              $checksum = 0;
              for ($i = 0; $i < 512; $i++) {
                $checksum += ord(substr($header,$i,1));
              }
              $checksum_data = pack("a8", sprintf("%6s ", decoct($checksum)));
              for ($i = 0, $j = 148; $i < 7; $i++, $j++) {
                $header[$j] = $checksum_data[$i];
              }  
              return $header;
        }
      }
      $tar = & new Tar_Archive("$tar_file");
	  break;
      case 'forget':
        $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");

        $messageStack->add_session(SUCCESS_LAST_RESTORE_CLEARED, 'success');

        twe_redirect(twe_href_link(FILENAME_BACKUP));
        break;
      case 'backupnow':
        twe_set_time_limit(0);
        $backup_file = 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
        $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');

        $schema = '# Twe Commerce, Open Source E-Commerce Solutions' . "\n" .
                  '# http://www.oldpa.com.tw' . "\n" .
                  '#' . "\n" .
                  '# Database Backup For ' . STORE_NAME . "\n" .
                  '# Copyright (c) ' . date('Y') . ' ' . STORE_OWNER . "\n" .
                  '#' . "\n" .
                  '# Database: ' . DB_DATABASE . "\n" .
                  '# Database Server: ' . DB_SERVER . "\n" .
                  '#' . "\n" .
                  '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n\n";
        fputs($fp, $schema);

        $tables = $db->Execute('show tables');

        while (!$tables->EOF) {
          list(,$table) = each($tables->fields);

          $schema = 'drop table if exists ' . $table . ';' . "\n" .
                    'create table ' . $table . ' (' . "\n";

          $table_list = array();
          $fields = $db->Execute("show fields from " . $table);
          while (!$fields->EOF) {
            $table_list[] = $fields->fields['Field'];

            $schema .= '  ' . $fields->fields['Field'] . ' ' . $fields->fields['Type'];

            if (strlen($fields->fields['Default']) > 0) $schema .= ' default \'' . $fields->fields['Default'] . '\'';

            if ($fields->fields['Null'] != 'YES') $schema .= ' not null';

            if (isset($fields->fields['Extra'])) $schema .= ' ' . $fields->fields['Extra'];

            $schema .= ',' . "\n";
            $fields->MoveNext();
          }

          $schema = ereg_replace(",\n$", '', $schema);

// add the keys
          $index = array();
          $keys = $db->Execute("show keys from " . $table);
          while (!$keys->EOF) {
            $kname = $keys->fields['Key_name'];

            if (!isset($index[$kname])) {
              $index[$kname] = array('unique' => !$keys->fields['Non_unique'],
                                     'columns' => array());
            }

            $index[$kname]['columns'][] = $keys->fields['Column_name'];
            $keys->MoveNext();
          }

          while (list($kname, $info) = each($index)) {
            $schema .= ',' . "\n";

            $columns = implode($info['columns'], ', ');
//echo $columns;echo "<br />";
            if ($kname == 'PRIMARY') {
              $schema .= '  PRIMARY KEY (' . $columns . ')';
            } elseif ($info['unique']) {
              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
            } else {
              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
            }
          }

          $schema .= "\n" . ');' . "\n\n";
          fputs($fp, $schema);

// dump the data
          $rows = $db->Execute("select " . implode(',', $table_list) . " from " . $table);
          while (!$rows->EOF) {
            $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';

            reset($table_list);
            while (list(,$i) = each($table_list)) {
              if (!isset($rows->fields[$i])) {
                $schema .= 'NULL, ';
              } elseif (twe_not_null($rows->fields[$i])) {
                $row = addslashes($rows->fields[$i]);
                $row = ereg_replace("\n#", "\n".'\#', $row);

                $schema .= '\'' . $row . '\', ';
              } else {
                $schema .= '\'\', ';
              }
            }

            $schema = ereg_replace(', $', '', $schema) . ');' . "\n";
            fputs($fp, $schema);
            $rows->MoveNext();
          }
          $tables->MoveNext();
        }

        fclose($fp);

        if (isset($_POST['download']) && ($_POST['download'] == 'yes')) {
          switch ($_POST['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.gz';
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.zip';
          }
		  if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Type: application/octetstream');
//            header('Content-Disposition: inline; filename="' . $backup_file . '"');
            header('Content-Disposition: attachment; filename=' . $backup_file);
            header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
            header("Cache-Control: must_revalidate, post-check=0, pre-check=0");
            header("Pragma: public");                                    
            header("Cache-control: private");                          
		  } else {
            header('Content-Type: application/x-octet-stream');
            header('Content-Disposition: attachment; filename=' . $backup_file);
            header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
            header("Pragma: no-cache");                                    
		  }
 
          readfile(DIR_FS_BACKUP . $backup_file);
          unlink(DIR_FS_BACKUP . $backup_file);

          exit;
        } else {
          switch ($_POST['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
          }

          $messageStack->add_session(SUCCESS_DATABASE_SAVED, 'success');
        }

        twe_redirect(twe_href_link(FILENAME_BACKUP));
        break;
      case 'restorenow':
      case 'restorelocalnow':
        twe_set_time_limit(0);

        if ($action == 'restorenow') {
          $read_from = $_GET['file'];

          if (file_exists(DIR_FS_BACKUP . $_GET['file'])) {
            $restore_file = DIR_FS_BACKUP . $_GET['file'];
            $extension = substr($_GET['file'], -3);

            if ( ($extension == 'sql') || ($extension == '.gz') || ($extension == 'zip') ) {
              switch ($extension) {
                case 'sql':
                  $restore_from = $restore_file;
                  $remove_raw = false;
                  break;
                case '.gz':
                  $restore_from = substr($restore_file, 0, -3);
                  exec(LOCAL_EXE_GUNZIP . ' ' . $restore_file . ' -c > ' . $restore_from);
                  $remove_raw = true;
                  break;
                case 'zip':
                  $restore_from = substr($restore_file, 0, -4);
                  exec(LOCAL_EXE_UNZIP . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);
                  $remove_raw = true;
              }

              if (isset($restore_from) && file_exists($restore_from) && (filesize($restore_from) > 15000)) {
                $fd = fopen($restore_from, 'rb');
                $restore_query = fread($fd, filesize($restore_from));
                fclose($fd);
              }
            }
          }
        } elseif ($action == 'restorelocalnow') {
          $sql_file = new upload('sql_file');

          if ($sql_file->parse() == true) {
            $restore_query = fread(fopen($sql_file->tmp_filename, 'r'), filesize($sql_file->tmp_filename));
            $read_from = $sql_file->filename;
          }
        }

        if (isset($restore_query)) {
          $sql_array = array();
          $sql_length = strlen($restore_query);
          $pos = strpos($restore_query, ';');
          for ($i=$pos; $i<$sql_length; $i++) {
            if ($restore_query[0] == '#') {
              $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
              continue;
            }
            if ($restore_query[($i+1)] == "\n") {
              for ($j=($i+2); $j<$sql_length; $j++) {
                if (trim($restore_query[$j]) != '') {
                  $next = substr($restore_query, $j, 6);
                  if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
                    for ($k=$j; $k<$sql_length; $k++) {
                      if ($restore_query[$k] == "\n") break;
                    }
                    $query = substr($restore_query, 0, $i+1);
                    $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                    $restore_query = $query . $restore_query;
                    $sql_length = strlen($restore_query);
                    $i = strpos($restore_query, ';')-1;
                    continue 2;
                  }
                  break;
                }
              }
              if ($next == '') { // get the last insert query
                $next = 'insert';
              }
              if ( (preg_match('/create/', $next)) || (preg_match('/insert/', $next)) || (preg_match('/drop t/', $next)) ) {
                $next = '';
                $sql_array[] = substr($restore_query, 0, $i);
                $restore_query = ltrim(substr($restore_query, $i+1));
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
              }
            }
          }

          $db->Execute("drop table if exists address_book, 
 address_format, 
 admin_access, 
 banners, 
 banners_history, 
 categories, 
 categories_description, 
 cm_file_flags, 
 configuration, 
 configuration_group, 
 content_manager, 
 counter, 
 counter_history, 
 countries, 
 coupons, 
 coupons_description, 
 coupon_email_track, 
 coupon_gv_customer, 
 coupon_gv_queue, 
 coupon_redeem_track, 
 currencies, 
 customers, 
 customers_basket, 
 customers_basket_attributes, 
 customers_info, 
 customers_ip, 
 customers_memo, 
 customers_status, 
 customers_status_history, 
 geo_zones, 
 languages, 
 layout_boxes, 
 layout_template, 
 manufacturers, 
 manufacturers_info, 
 media_content, 
 module_newsletter, 
 newsletters, 
 newsletters_history, 
 news_categories, 
 news_categories_description, 
 news_products, 
 news_products_description, 
 news_products_to_categories, 
 orders, 
 orders_products, 
 orders_products_attributes, 
 orders_products_download, 
 orders_status, 
 orders_status_history, 
 orders_total, 
 personal_offers_by_customers_status_0, 
 personal_offers_by_customers_status_1, 
 personal_offers_by_customers_status_2, 
 phpbb_auth_access, 
 phpbb_banlist, 
 phpbb_categories, 
 phpbb_config, 
 phpbb_confirm, 
 phpbb_disallow, 
 phpbb_forums, 
 phpbb_forum_prune, 
 phpbb_groups, 
 phpbb_posts, 
 phpbb_posts_text, 
 phpbb_privmsgs, 
 phpbb_privmsgs_text, 
 phpbb_ranks, 
 phpbb_search_results, 
 phpbb_search_wordlist, 
 phpbb_search_wordmatch, 
 phpbb_sessions, 
 phpbb_smilies, 
 phpbb_themes, 
 phpbb_themes_name, 
 phpbb_topics, 
 phpbb_topics_watch, 
 phpbb_user_group, 
 phpbb_vote_desc, 
 phpbb_vote_results, 
 phpbb_vote_voters, 
 phpbb_words, 
 products, 
 products_attributes, 
 products_attributes_download, 
 products_content, 
 products_description, 
 products_graduated_prices, 
 products_more_images, 
 products_notifications, 
 products_options, 
 products_options_values, 
 products_options_values_to_products_options, 
 products_to_categories, 
 reviews, 
 reviews_description, 
 sessions, 
 shipping_status, 
 specials, 
 tax_class, 
 tax_rates, 
 whos_online, 
 zones, 
 zones_to_geo_zones");

          for ($i=0, $n=sizeof($sql_array); $i<$n; $i++) {
            $db->Execute($sql_array[$i]);
          }

          $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");
	      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key,configuration_value,configuration_group_id) values ('DB_LAST_RESTORE', '" . $read_from . "','6')");

          if (isset($remove_raw) && ($remove_raw == true)) {
            unlink($restore_from);
          }

          $messageStack->add_session(SUCCESS_DATABASE_RESTORED, 'success');
        }

        twe_redirect(twe_href_link(FILENAME_BACKUP));
        break;
      case 'download':
        $extension = substr($_GET['file'], -3);

        if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
          if ($fp = fopen(DIR_FS_BACKUP . $_GET['file'], 'rb')) {
            $buffer = fread($fp, filesize(DIR_FS_BACKUP . $_GET['file']));
            fclose($fp);

            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $_GET['file']);

            echo $buffer;

            exit;
          }
        } else {
          $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;
      case 'deleteconfirm':
        if (strstr($_GET['file'], '..')) twe_redirect(twe_href_link(FILENAME_BACKUP));

        twe_remove(DIR_FS_BACKUP . '/' . $_GET['file']);

        if (!$twe_remove_error) {
          $messageStack->add_session(SUCCESS_BACKUP_DELETED, 'success');

          twe_redirect(twe_href_link(FILENAME_BACKUP));
        }
        break;
    }
  }

// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (is_writeable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($dir_ok == true) {
    $dir = dir(DIR_FS_BACKUP);
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
        if ($file != '.empty') {
          $contents[] = $file;
        }
      }
    }
    sort($contents);

    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      $entry = $contents[$i];

      $check = 0;

      if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry))) && !isset($buInfo) && ($action != 'backup') && ($action != 'restorelocal')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
        $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';
        switch (substr($entry, -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; break;
          default: $file_array['compression'] = TEXT_NO_EXTENSION; break;
        }

        $buInfo = new objectInfo($file_array);
      }

      if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        $onclick_link = 'file=' . $buInfo->file . '&action=restore';
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        $onclick_link = 'file=' . $entry;
      }
?>
                <td class="dataTableContent" onClick="document.location.href='<?php echo twe_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo '<a href="' . twe_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry) . '">' . twe_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
                <td class="dataTableContent" align="center" onClick="document.location.href='<?php echo twe_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                <td class="dataTableContent" align="right" onClick="document.location.href='<?php echo twe_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> bytes</td>
                <td class="dataTableContent" align="right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $entry) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
    $dir->close();
  }
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                <td align="right" class="smallText"><?php if ( ($action != 'backup') && (isset($dir)) ) echo '<a href="' . twe_href_link(FILENAME_BACKUP, 'action=backup') . '">' . twe_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>'; if ( ($action != 'restorelocal') && isset($dir) ) echo '<br><a href="' . twe_href_link(FILENAME_BACKUP, 'action=restorelocal') . '">' . twe_image_button('button_restore.gif', IMAGE_RESTORE) . '</a><br><a href="' . twe_href_link(FILENAME_BACKUP, 'action=backup_file')  .'">' .twe_image_button('backup_file.gif', IMAGE_BACKUP) . '</a>'; ?></td>
              </tr>
<?php
  if (defined('DB_LAST_RESTORE')) {
?>
              <tr>
                <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . twe_href_link(FILENAME_BACKUP, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'backup':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>');

      $contents = array('form' => twe_draw_form('backup', FILENAME_BACKUP, 'action=backupnow'));
      $contents[] = array('text' => TEXT_INFO_NEW_BACKUP);

      $contents[] = array('text' => '<br>' . twe_draw_radio_field('compress', 'no', true) . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
      if (file_exists(LOCAL_EXE_GZIP)) $contents[] = array('text' => '<br>' . twe_draw_radio_field('compress', 'gzip') . ' ' . TEXT_INFO_USE_GZIP);
      if (file_exists(LOCAL_EXE_ZIP)) $contents[] = array('text' => twe_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);

      if ($dir_ok == true) {
        $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      } else {
        $contents[] = array('text' => '<br>' . twe_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_backup.gif', IMAGE_BACKUP) . '&nbsp;<a href="' . twe_href_link(FILENAME_BACKUP) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restore':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents[] = array('text' => twe_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restorenow') . '">' . twe_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>&nbsp;<a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restorelocal':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</b>');

      $contents = array('form' => twe_draw_form('restore', FILENAME_BACKUP, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS);
      $contents[] = array('text' => '<br>' . twe_draw_file_field('sql_file'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_restore.gif', IMAGE_RESTORE) . '&nbsp;<a href="' . twe_href_link(FILENAME_BACKUP) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents = array('form' => twe_draw_form('delete', FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $buInfo->file . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($buInfo) && is_object($buInfo)) {
        $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restore') . '">' . twe_image_button('button_restore.gif', IMAGE_RESTORE) . '</a> <a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE . ' ' . $buInfo->date);
        $contents[] = array('text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression);
      }
	 
      break;
  }

  if ( (twe_not_null($heading)) && (twe_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>