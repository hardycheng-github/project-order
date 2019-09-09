<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<td class="maintitle" valign="top">&nbsp;<a href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a></td>
<td class="gensmall" align="right" valign="bottom"><a href="{U_VIEW_NEWER_TOPIC}">{L_VIEW_NEXT_TOPIC}</a><br />
<a href="{U_VIEW_OLDER_TOPIC}">{L_VIEW_PREVIOUS_TOPIC}</a><br />
<strong>{S_WATCH_TOPIC}</strong></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/spacer.gif" alt="" width="15" height="26" /></td>
		<td valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="templates/fiapplegreen/images/lang_english/tabt.gif" alt="" width="80" height="6" /></td>
					<td><img src="templates/fiapplegreen/images/lang_english/tabt.gif" alt="" width="80" height="6" /></td>
				</tr>
				<tr>
					<td class="postbkg"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></td>
					<td class="postbkg"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}" /></a></td>
				</tr>
			</table>
		</td>
		<td class="nav" width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}">{L_INDEX}</a> &raquo; <a href="{U_VIEW_FORUM}">{FORUM_NAME}</a></td>
		<td class="nav" nowrap="nowrap">{PAGINATION}&nbsp;</td>
	</tr>
</table>
{POLL_DISPLAY}
<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0">
<tr>
<th width="150" height="28">{L_AUTHOR}</th>
<th width="100%">{L_MESSAGE}</th>
</tr>
<!-- BEGIN postrow -->
<tr>
<td valign="top" class="{postrow.ROW_CLASS}" rowspan="2"><span class="name"><a name="{postrow.U_POST_ID}" id="{postrow.U_POST_ID}"></a><strong>{postrow.POSTER_NAME}</strong></span><br />
<span class="postdetails">{postrow.POSTER_RANK}<br />
{postrow.RANK_IMAGE}{postrow.POSTER_AVATAR}<br /><br />
{postrow.POSTER_JOINED}<br />
{postrow.POSTER_POSTS}<br />
{postrow.POSTER_FROM}</span><br />
<img src="images/spacer.gif" alt="" width="150" height="1" />
</td>
<td class="{postrow.ROW_CLASS}" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="postdetails"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a>{L_POSTED}: 
{postrow.POST_DATE}</td>
<td align="right" valign="top" nowrap="nowrap">{postrow.QUOTE_IMG}{postrow.EDIT_IMG}<a href="#top"><img src="templates/fiapplegreen/images/icon_up.gif" alt="{L_BACK_TO_TOP}" width="24" height="23" title="{L_BACK_TO_TOP}" /></a>{postrow.DELETE_IMG}{postrow.IP_IMG}</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top" class="postbody">
<hr />
{postrow.MESSAGE}</td>
</tr>
<tr>
<td height="40" valign="bottom" class="genmed">{postrow.SIGNATURE}<span class="postdetails">{postrow.EDITED_MESSAGE}</span></td>
</tr>
</table>
</td>
</tr>
<tr>
<td valign="bottom" nowrap="nowrap" class="{postrow.ROW_CLASS}">{postrow.PROFILE_IMG}{postrow.PM_IMG}{postrow.EMAIL_IMG}{postrow.WWW_IMG}{postrow.AIM_IMG}{postrow.YIM_IMG}{postrow.MSN_IMG}{postrow.ICQ_IMG}</td>
</tr>
<tr>
<td class="spacerow" colspan="2" height="1"><img src="images/spacer.gif" alt="" width="1" height="1" /></td>
</tr>
<!-- END postrow -->
<tr>
<td colspan="2" align="center" class="cat"> 
<form method="post" action="{S_POST_DAYS_ACTION}">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td class="gensmall">{L_DISPLAY_POSTS}:&nbsp;&nbsp;</td>
<td>{S_SELECT_POST_DAYS}&nbsp;</td>
<td>{S_SELECT_POST_ORDER}&nbsp;</td>
<td><input type="submit" value="{L_GO}" class="catbutton" name="submit" /></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/spacer.gif" alt="" width="15" height="26" /></td>
		<td valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="templates/fiapplegreen/images/lang_english/tabsp.gif" alt="" width="80" height="2" /></td>
					<td><img src="templates/fiapplegreen/images/lang_english/tabsp.gif" alt="" width="80" height="2" /></td>
				</tr>
				<tr>
					<td class="postbkg"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></td>
					<td class="postbkg"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}" /></a></td>
				</tr>
				<tr>
					<td><img src="templates/fiapplegreen/images/lang_english/tabb.gif" alt="" width="80" height="6" /></td>
					<td><img src="templates/fiapplegreen/images/lang_english/tabb.gif" alt="" width="80" height="6" /></td>
				</tr>
			</table>
		</td>
		<td class="nav" width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}">{L_INDEX}</a> &raquo; <a href="{U_VIEW_FORUM}">{FORUM_NAME}</a></td>
		<td class="nav" nowrap="nowrap">{PAGINATION}&nbsp;</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<td><br />
<br />
{JUMPBOX}<br />
<br />
{S_TOPIC_ADMIN}</td>
<td class="gensmall" align="right" valign="top"><strong>{S_WATCH_TOPIC}</strong><br />
<a href="{U_VIEW_NEWER_TOPIC}">{L_VIEW_NEXT_TOPIC}</a><br />
<a href="{U_VIEW_OLDER_TOPIC}">{L_VIEW_PREVIOUS_TOPIC}</a><br />
{S_AUTH_LIST}</td>
</tr>
</table>