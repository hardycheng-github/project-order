<?php
/***************************************************************************
 *                                sessions.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: sessions.php,v 1.58.2.14 2005/05/06 20:50:11 acydburn Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *   Integrate PHPBB2.0.11 INTO TWE-Commerce  Copyright (c) 2004/11/20  
 *     author mail liang.ishiang@msa.hinet.net	
 *     author:		Oldpa
 *     web site:    http://www.oldpa.com.tw  
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
//
// Adds/updates a new session to the database for the given userid.
// Returns the new session ID on success.
//
function session_pagestart($user_ip, $thispage_id)
{
	global $db, $lang, $board_config,$SID,$page_id;
	global $HTTP_COOKIE_VARS, $HTTP_GET_VARS;
	
	$cookiename = $board_config['cookie_name'];
	$cookiepath = $board_config['cookie_path'];
	$cookiedomain = $board_config['cookie_domain'];
	$cookiesecure = $board_config['cookie_secure'];	

	$current_time = time();
	$last_visit = 0;
	$sessionmethod = SESSION_METHOD_GET;
	
	if ( isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) || isset($HTTP_COOKIE_VARS[$cookiename . '_data']) )
	{
		$sessiondata = isset( $HTTP_COOKIE_VARS[$cookiename . '_data'] ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$cookiename . '_data'])) : array();
		$session_id = isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) ? $HTTP_COOKIE_VARS[$cookiename . '_sid'] : '';
		$sessionmethod = SESSION_METHOD_COOKIE;
	}
	else
	{
		$sessiondata = array();
		$session_id = isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) ? $HTTP_COOKIE_VARS[$cookiename . '_sid'] : '';
		$sessionmethod = SESSION_METHOD_GET;
	}		

	if (isset($_SESSION['customer_id'])){
	  $session_id = twe_session_id();
	  $customers_id = $_SESSION['customer_id'];
	  $login = '1';
	} else {
	  $session_id = twe_session_id();
	  $customers_id = ANONYMOUS;
	  $login = '0';
	}

	$expiry_time = $current_time - $board_config['session_length'];
					$sql = "DELETE FROM " . SESSIONS_TABLE . " 
						WHERE session_time < $expiry_time 
							AND session_id <> '$session_id'";
					if ( !$db->sql_query($sql) )
					{
						message_die(CRITICAL_ERROR, 'Error clearing sessions table', '', __LINE__, __FILE__, $sql);
					}

    $stored_customer_query = "select count(*) as count from " . TABLE_PHPBB_SESSIONS . " where session_id = '" . $session_id . "'";
    $stored_customer =  $db->Execute($stored_customer_query);

    if ($stored_customer->fields['count'] > 0) {
       $db->Execute("update " . TABLE_PHPBB_SESSIONS . " set session_user_id = '" . $customers_id . "', session_start = '".$current_time."', session_time = '" . $current_time . "', session_page = '".$page_id."', session_logged_in = '". $login ."' where session_id = '" . $session_id ."' and session_ip = '".$user_ip."'");
	} else {
       $db->Execute("insert into " . TABLE_PHPBB_SESSIONS . " (session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in) values ('" . $session_id . "', '" . $customers_id . "', '" . $current_time . "', '" . $current_time . "', '" . $user_ip . "', '".$page_id."', '". $login ."')");
    }
	
	//
	// Does a session exist?
	//
	if ( !empty($session_id) )
	{
		//
		// session_id exists so go ahead and attempt to grab all
		// data in preparation
		//
		$sql = "SELECT u.*, s.*
			FROM " . SESSIONS_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.session_id = '$session_id'
				AND u.customers_id = s.session_user_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, 'Error doing DB query userdata row fetch', '', __LINE__, __FILE__, $sql);
		}

		$userdata = $db->sql_fetchrow($result);

		//
		// Did the session exist in the DB?
		//
		if ( isset($userdata['customers_id']) )
		{
			//
			// Do not check IP assuming equivalence, if IPv4 we'll check only first 24
			// bits ... I've been told (by vHiker) this should alleviate problems with 
			// load balanced et al proxies while retaining some reliance on IP security.
			//
			$ip_check_s = substr($userdata['session_ip'], 0, 6);
			$ip_check_u = substr($user_ip, 0, 6);

			if ($ip_check_s == $ip_check_u)
			{
				$SID = ($sessionmethod == SESSION_METHOD_GET || defined('IN_ADMIN')) ? 'Twesid=' . $session_id : '';

				//
				// Only update session DB a minute or so after last update
				//
				if ( $current_time - $userdata['session_time'] > 60 )
				{
					$sql = "UPDATE " . SESSIONS_TABLE . " 
						SET session_time = $current_time, session_page = $thispage_id 
						WHERE session_id = '" . $userdata['session_id'] . "'";
					if ( !$db->sql_query($sql) )
					{
						message_die(CRITICAL_ERROR, 'Error updating sessions table', '', __LINE__, __FILE__, $sql);
					}

					if ( $userdata['customers_id'] != ANONYMOUS )
					{
						$sql = "UPDATE " . USERS_TABLE . " 
							SET user_session_time = $current_time, user_session_page = $thispage_id 
							WHERE customers_id = " . $userdata['customers_id'];
						if ( !$db->sql_query($sql) )
						{
							message_die(CRITICAL_ERROR, 'Error updating sessions table', '', __LINE__, __FILE__, $sql);
						}
					}
					setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);
					setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);
					}

				return $userdata;
			}
		}
	}
}
//
// session_end closes out a session
// deleting the corresponding entry
// in the sessions table
//
function session_end($session_id, $customers_id)
{
	global $db, $lang, $board_config, $cart;
	global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;

	$cookiename = $board_config['cookie_name'];
	$cookiepath = $board_config['cookie_path'];
	$cookiedomain = $board_config['cookie_domain'];
	$cookiesecure = $board_config['cookie_secure'];

	$current_time = time();

	//
	// Pull cookiedata or grab the URI propagated sid
	//
	if ( isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) )
	{
		$session_id = isset( $HTTP_COOKIE_VARS[$cookiename . '_sid'] ) ? $HTTP_COOKIE_VARS[$cookiename . '_sid'] : '';
		$sessionmethod = SESSION_METHOD_COOKIE;
	}
	else
	{
		$session_id = ( isset($HTTP_GET_VARS['Twesid']) ) ? $HTTP_GET_VARS['Twesid'] : '';
		$sessionmethod = SESSION_METHOD_GET;
	}

	//
	// Delete existing session
	//
	$sql = "DELETE FROM " . SESSIONS_TABLE . " 
		WHERE session_id = '$session_id' 
			AND session_user_id = $customers_id";
	if ( !$db->sql_query($sql) )
	{
		message_die(CRITICAL_ERROR, 'Error removing user session', '', __LINE__, __FILE__, $sql);
	}

	setcookie($cookiename . '_data', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
	setcookie($cookiename . '_sid', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);

	twe_session_destroy();
	unset($_SESSION['customer_id']);
	unset($_SESSION['customer_default_address_id']);
	unset($_SESSION['customer_first_name']);
	unset($_SESSION['customer_country_id']);
	unset($_SESSION['customer_zone_id']);
	unset($_SESSION['comments']);
	unset($_SESSION['user_info']);
	unset($_SESSION['customers_status']);
	unset($_SESSION['selected_box']);
	unset($_SESSION['navigation']);
	unset($_SESSION['shipping']);
	unset($_SESSION['payment']);
	// GV Code Start
	unset($_SESSION['gv_id']);
	unset($_SESSION['cc_id']);
	// GV Code End
	$_SESSION['cart']->reset();

	return true;
}

//
// Append $SID to a url. Borrowed from phplib and modified. This is an
// extra routine utilised by the session code above and acts as a wrapper
// around every single URL and form action. If you replace the session
// code you must include this routine, even if it's empty.
//
function append_sid($url, $non_html_amp = false)
{
	global $SID;

	if ( !empty($SID) && !preg_match('#Twesid=#', $url) )
	{
		$url .= ( ( strpos($url, '?') != false ) ?  ( ( $non_html_amp ) ? '&' : '&amp;' ) : '?' ) . $SID;
	}

	return $url;
}
?>