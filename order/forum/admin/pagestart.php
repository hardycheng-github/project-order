<?php
/***************************************************************************
 *                               pagestart.php
 *                            -------------------
 *   begin                : Thursday, Aug 2, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: pagestart.php,v 1.1.2.9 2005/06/26 14:39:30 acydburn Exp $
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

if (!defined('IN_PHPBB'))
{
	die("Hacking attempt");
}

define('IN_ADMIN', true);
define('ADMIN', true);

// Include files
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if (!$userdata['session_logged_in'])
{
	redirect(append_sid("login.$phpEx?redirect=admin/index.$phpEx", true));
}
else if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_admin']);
}

if ($HTTP_GET_VARS['Twesid'] != $userdata['session_id'])
{
	$url = str_replace(preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name'])), '', $HTTP_SERVER_VARS['REQUEST_URI']);
	$url = str_replace(preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path'])), '', $url);
	$url = str_replace('//', '/', $url);
	$url = preg_replace('/Twesid=([^&]*)(&?)/i', '', $url);
	$url = preg_replace('/\?$/', '', $url);
	$url .= ((strpos($url, '?')) ? '&' : '?') . 'Twesid=' . $userdata['session_id'];

	redirect("index.$phpEx?Twesid=" . $userdata['session_id']);
}

if (empty($no_page_header))
{
	// Not including the pageheader can be neccesarry if META tags are
	// needed in the calling script.
	include('./page_header_admin.'.$phpEx);
}

?>