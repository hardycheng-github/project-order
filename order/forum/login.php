<?php
/***************************************************************************
 *                                login.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: login.php,v 1.47.2.18 2005/05/06 20:50:10 acydburn Exp $
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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);

$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
require_once(DIR_FS_INC . 'twe_validate_password.inc.php');
$breadcrumb->add(NAVBAR_TITLE_BBFORUM, twe_href_link(FILENAME_BBFORUM, '', 'SSL'));
global $db,$row;

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['Twesid']) || !empty($HTTP_GET_VARS['Twesid']))
{
	$sid = (!empty($HTTP_POST_VARS['Twesid'])) ? $HTTP_POST_VARS['Twesid'] : $HTTP_GET_VARS['Twesid'];
}
else
{
	$sid = '';
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) )
{
	if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && !$userdata['session_logged_in'] )
	{
		$email_address = twe_db_prepare_input($_POST['email_address']);
		$password = twe_db_prepare_input($_POST['password']);

		$check_customer_query = "select customers_id, customers_firstname,customers_lastname, customers_gender, customers_password, customers_email_address, customers_default_address_id, username, user_active, user_level from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($email_address) . "'";

		$row = $db->Execute($check_customer_query);

		if( $row->RecordCount() >0){
			if( $row->fields['user_level'] != ADMIN && $board_config['board_disable'] ){
				redirect(append_sid("index.$phpEx", true));
			}else{
			
				if( twe_validate_password($password,$row->fields['customers_password']) == $row->fields['customers_password'] )
				{

					// $autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

					$check_country_query = "select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$row->fields['customers_id'] . "' and address_book_id = '" . $row->fields['customers_default_address_id'] . "'";
					$check_country = $db->Execute($check_country_query);
		
					$_SESSION['customer_id'] = $row->fields['customers_id'];
					$_SESSION['customer_last_name'] = $row->fields['customers_lastname'];
					$_SESSION['customer_username'] = $row->fields['username'];
					$_SESSION['customer_default_address_id'] = $row->fields['customers_default_address_id'];
					$_SESSION['customer_first_name'] = $row->fields['customers_firstname'];
					$_SESSION['customer_country_id'] = $check_country->fields['entry_country_id'];
					$_SESSION['customer_zone_id'] = $check_country->fields['entry_zone_id'];

					$db->Execute("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
					// restore cart contents
					$_SESSION['cart']->restore_contents();
					$session_id = twe_session_id();

					if( $session_id )
					{
						$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "index.$phpEx";
						redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				else
				{
					$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : '';
					$redirect = str_replace('?', '&', $redirect);

					$template->assign_vars(array(
						'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
					);

					$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}
		}
		else
		{
			$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "";
			$redirect = str_replace("?", "&", $redirect);

			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
			);

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
	{
		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['customers_id']);
		}

		if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
		{
			$url = (!empty($HTTP_POST_VARS['redirect'])) ? $HTTP_POST_VARS['redirect'] : $HTTP_GET_VARS['redirect'];
			redirect(append_sid($url, true));
		}
		else
		{
			redirect(append_sid("index.$phpEx", true));
		}
	}
	else
	{
		$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "index.$phpEx";
		redirect(append_sid($url, true));
	}
}
else
{
	//
	// Do a full login page dohickey if
	// user not already logged in
	//
	if( !$userdata['session_logged_in'] )
	{
		$page_title = $lang['Login'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'login_body.tpl')
		);

		if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
		{
			$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

			if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
			{
				$forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(count($forward_match) > 1)
				{
					$forward_page = '';

					for($i = 1; $i < count($forward_match); $i++)
					{
						if( !preg_match("/Twesid=/", $forward_match[$i]) )
						{
							if( $forward_page != '' )
							{
								$forward_page .= '&';
							}
							$forward_page .= $forward_match[$i];
						}
					}
					$forward_page = $forward_match[0] . '?' . $forward_page;
				}
				else
				{
					$forward_page = $forward_match[0];
				}
			}
		}
		else
		{
			$forward_page = '';
		}

		$username = ( $userdata['customers_id'] != ANONYMOUS ) ? $userdata['username'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';

		make_jumpbox('viewforum.'.$phpEx, $forum_id);
		$template->assign_vars(array(
			'USERNAME' => $username,
            
			'L_ENTER_PASSWORD' => $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],

			'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
	else
	{
		redirect(append_sid("index.$phpEx", true));
	}
}

?>