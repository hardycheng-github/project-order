<?php
/* -----------------------------------------------------------------------------------------
   $Id: sessions.php,v 1.1 2003/09/06 22:13:54 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(sessions.php,v 1.16 2003/04/02); www.oscommerce.com 
   (c) 2003	 nextcommerce (sessions.php,v 1.5 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
	  global $db;
	  if (!is_object($db)) {
	    $db = ADONewConnection(DB_TYPE);
        $db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, USE_PCONNECT, false);
      }
      $qid = "select value from " . TABLE_SESSIONS . " where sesskey = '" . $key . "' and expiry > '" . time() . "'";

      $value = $db->Execute($qid);
     if ($value->fields['value']) {
        return $value->fields['value'];
      }
      return false;
    }

    function _sess_write($key, $val) {
      global $db;
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = addslashes($val);
if (!is_object($db)) {
	    $db = ADONewConnection(DB_TYPE);
        $db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, USE_PCONNECT, false);
      }
      $qid = "select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . $key . "'";
      $total = $db->Execute($qid);

      if ($total->fields['total'] > 0) {
        $sql = "update " . TABLE_SESSIONS . " set expiry = '" . $expiry . "', value = '" . $value . "' where sesskey = '" . $key . "'";
        return $db->Execute($sql);
	  } else {
        $sql = "insert into " . TABLE_SESSIONS . " values ('" . $key . "', '" . $expiry . "', '" . $value . "')";
          return $db->Execute($sql);
      }
    }

    function _sess_destroy($key) {
	global $db;
	if (!is_object($db)) {
	    $db = ADONewConnection(DB_TYPE);
        $db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, USE_PCONNECT, false);
      }
      $sql = "delete from " . TABLE_SESSIONS . " where sesskey = '" . $key . "'";
	 return $db->Execute($sql);
     }

    function _sess_gc($maxlifetime) {
     global $db;
	 if (!is_object($db)) {
	    $db = ADONewConnection(DB_TYPE);
        $db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, USE_PCONNECT, false);
      }
      $sql = "delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'";
      $db->Execute($sql);
      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function twe_session_start() {
    return session_start();
  }

  function twe_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
      return session_register($variable);
    }
  }

  function twe_session_is_registered($variable) {
    return session_is_registered($variable);
  }

  function twe_session_unregister($variable) {
    return session_unregister($variable);
  }

  function twe_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function twe_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function twe_session_close() {
    if (function_exists('session_close')) {
      return session_close();
    }
  }

  function twe_session_destroy() {
    return session_destroy();
  }

  function twe_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function twe_session_recreate() {
    if (PHP_VERSION >= 4.1) {
      $session_backup = $_SESSION;

      unset($_COOKIE[twe_session_name()]);

      twe_session_destroy();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
      }

      twe_session_start();

      $_SESSION = $session_backup;
      unset($session_backup);
    }
  }
?>