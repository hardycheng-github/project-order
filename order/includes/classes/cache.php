<?php
/* -----------------------------------------------------------------------------------------
   $Id: cache.php, 2005/04/15 22:38:22 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------*/


class cache {

  function sql_cache_exists($twef_query) {
    global $db;
    $twep_cache_name = $this->cache_generate_cache_name($twef_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
        // where using a single directory at the moment. Need to look at splitting into subdirectories
	// like adodb
	if (file_exists(DIR_FS_SQL_CACHE . '/' . $twep_cache_name . '.sql')) {
	  return true;
	} else {
          return false;
	}
      break;
      case 'database':
        $sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $twep_cache_name . "'";
	$twep_cache_exists = $db->Execute($sql);
	if ($twep_cache_exists->RecordCount() > 0) {
	  return true;
	} else {
          return false;
	}
      break;
      case 'memory':
        return false;
      break;
      case 'none':
        return false;
      break;
    }
  }

  function sql_cache_is_expired($twef_query, $twef_cachetime) {
    global $db;
    $twep_cache_name = $this->cache_generate_cache_name($twef_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
        if (filemtime(DIR_FS_SQL_CACHE . '/' . $twep_cache_name . '.sql') > (time() - $twef_cachetime)) {
	  return false;
	} else {
          return true;
	}
      break;
      case 'database':
        $sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $twep_cache_name ."'";
	$cache_result = $db->Execute($sql);
	if ($cache_result->RecordCount() > 0) {
	  $start_time = $cache_result->fields['cache_entry_created'];
	  if (time() - $start_time > $twef_cachetime) return true;
	  return false;
	} else {
          return true;
	}
      break;
      case 'memory':
        return true;
      break;
      case 'none':
        return true;
      break;
    }
  }

  function sql_cache_expire_now($twef_query) {
    global $db;
    $twep_cache_name = $this->cache_generate_cache_name($twef_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
        @unlink(DIR_FS_SQL_CACHE . '/' . $twep_cache_name . '.sql');
        return true;
      break;
      case 'database':
       $db->Execute("delete from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $twep_cache_name . "'");
	
        return true;
      break;
      case 'memory':
        unset($this->cache_array[$twep_cache_name]);
        return true;
      break;
      case 'none':
        return true;
      break;
    }
  }
  
  function sql_cache_store($twef_query, $twef_result_array) {
    global $db;
    $twep_cache_name = $this->cache_generate_cache_name($twef_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
        $OUTPUT = serialize($twef_result_array); 
        $fp = fopen(DIR_FS_SQL_CACHE . '/' . $twep_cache_name . '.sql',"w"); 
        fputs($fp, $OUTPUT); 
        fclose($fp);
        return true;
      break;
      case 'database':
        $result_serialize = $db->prepare_input(serialize($twef_result_array));
	$db->Execute("insert into " . TABLE_DB_CACHE . " set cache_entry_name = '" . $twep_cache_name . "', 
	                                               cache_data = '" . $result_serialize . "',
						       cache_entry_created = '" . time() . "'");
	
        return true;
      break;
      case 'memory':
        return true;
      break;
      case 'none':
        return true;
      break;
    }
  }
  
  function sql_cache_read($twef_query) {
    global $db;
    $twep_cache_name = $this->cache_generate_cache_name($twef_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
        $twep_fa = file(DIR_FS_SQL_CACHE . '/' . $twep_cache_name . '.sql');
	$twep_result_array = unserialize(implode('', $twep_fa));
        return $twep_result_array;
      break;
      case 'database':
	$sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $twep_cache_name . "'";
	$twep_cache_result = $db->Execute($sql);
	$twep_result_array = unserialize($twep_cache_result->fields['cache_data']); 
        return $twep_result_array;
      break;
      case 'memory':
        return true;
      break;
      case 'none':
        return true;
      break;
    }
  }
  
  function sql_cache_flush_cache() {
    global $db;
    switch (SQL_CACHE_METHOD) {
      case 'file':
        if ($twea_dir = @dir(DIR_FS_SQL_CACHE)) {
          while ($twev_file = $twea_dir->read()) {
            if (strstr($twev_file, '.sql') && strstr($twev_file, 'twec_')) {
              @unlink(DIR_FS_SQL_CACHE . '/' . $twev_file);
            }
          }
        }
        return true;
      break;
      case 'database':
        $sql = "delete from " . TABLE_DB_CACHE;
	$db->Execute($sql);
        return true;
      break;
      case 'memory':
        return true;
      break;
      case 'none':
        return true;
      break;
    }
  }
  
  function cache_generate_cache_name($twef_query) {
    switch (SQL_CACHE_METHOD) {
      case 'file':
        return 'twec_' . md5($twef_query);
      break;
      case 'database':
        return 'twec_' . md5($twef_query);
      break;
      case 'memory':
        return 'twec_' . md5($twef_query);
      break;
      case 'none':
        return true;
      break;
    }
  }
}
?>