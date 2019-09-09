<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
<td class="maintitle"><a href="{U_VIEW_FORUM}">{FORUM_NAME}</a></td>
<td class="gensmall" align="right" valign="bottom">{L_MODERATOR}: {MODERATORS}<br />
{LOGGED_IN_USER_LIST}<br />
<strong><a href="{U_MARK_READ}">{L_MARK_TOPICS_READ}</a></strong></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/spacer.gif" alt="" width="15" height="26" /></td>
		<td valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="templates/fiapplegreen/images/lang_english/tabt.gif" alt="" width="80" height="6" /></td>
				</tr>
				<tr>
					<td class="postbkg"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></td>
				</tr>
			</table>
		</td>
		<td class="nav" width="100%">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}">{L_INDEX}</a> &raquo; <a href="{U_VIEW_FORUM}">{FORUM_NAME}</a></td>
		<td class="nav" nowrap="nowrap">{PAGINATION}&nbsp;</td>
	</tr>
</table>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="forumline">
	<tr>
		<th colspan="2">{L_TOPICS}</th>
		<th>{L_REPLIES}</th>
		<th>{L_AUTHOR}</th>
		<th>{L_VIEWS}</th>
		<th>{L_LASTPOST}</th>
	</tr>
	<!-- BEGIN topicrow -->
	<tr>
		<td height="34" class="row1"><a href="{topicrow.U_VIEW_TOPIC}"><img src="{topicrow.TOPIC_FOLDER_IMG}" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" /></a></td>
		<td class="row1" width="100%">{topicrow.NEWEST_POST_IMG}<span class="topictitle">{topicrow.TOPIC_TYPE}</span><a href="{topicrow.U_VIEW_TOPIC}" class="topictitle">{topicrow.TOPIC_TITLE}</a><span class="gensmall"><br />
			{topicrow.GOTO_PAGE}</span></td>
		<td class="row2" align="center"><span class="gensmall">{topicrow.REPLIES}</span></td>
		<td class="row3" align="center" nowrap="nowrap"><span class="gensmall">&nbsp;{topicrow.TOPIC_AUTHOR}&nbsp;</span></td>
		<td class="row2" align="center"><span class="gensmall">{topicrow.VIEWS}</span></td>
		<td class="row3" align="center" nowrap="nowrap"><span class="gensmall">&nbsp;{topicrow.LAST_POST_TIME}&nbsp;<br />
			{topicrow.LAST_POST_AUTHOR} {topicrow.LAST_POST_IMG}</span></td>
	</tr>
	<!-- END topicrow -->
	<!-- BEGIN switch_no_topics -->
	<tr>
		<td height="40" colspan="6" align="center" class="row1">{L_NO_TOPICS}</td>
	</tr>
	<!-- END switch_no_topics -->
	<tr>
		<td class="cat" align="center" colspan="6">
			<form method="post" action="{S_POST_DAYS_ACTION}">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="gensmall">{L_DISPLAY_TOPICS}:&nbsp;</td>
						<td>{S_SELECT_TOPIC_DAYS}&nbsp;</td>
						<td><input type="submit" class="catbutton" value="{L_GO}" name="submit" />
						</td>
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
				</tr>
				<tr>
					<td class="postbkg"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></td>
				</tr>
				<tr>
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
<td><br />{JUMPBOX}</td>
<td class="gensmall" align="right" valign="top"><strong><a href="{U_MARK_READ}">{L_MARK_TOPICS_READ}</a></strong><br />
{L_MODERATOR}: {MODERATORS}<br />
{LOGGED_IN_USER_LIST}
</td>
</tr>
</table>
<br />
<table class="forumline" width="100%" cellspacing="1" border="0" cellpadding="4">
	<tr>
		<th colspan="2">Key</th>
	</tr>
	<tr>
		<td class="row2" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="{FOLDER_NEW_IMG}" alt="{L_NEW_POSTS}" title="{L_NEW_POSTS}" /></td>
					<td class="gensmall">&nbsp;{L_NEW_POSTS}</td>
					<td>&nbsp;&nbsp;</td>
					<td><img src="{FOLDER_IMG}" alt="{L_NO_NEW_POSTS}" title="{L_NO_NEW_POSTS}" /></td>
					<td class="gensmall">&nbsp;{L_NO_NEW_POSTS}</td>
					<td>&nbsp;&nbsp;</td>
					<td><img src="{FOLDER_ANNOUNCE_IMG}" alt="{L_ANNOUNCEMENT}" title="{L_ANNOUNCEMENT}" /></td>
					<td class="gensmall">{L_ANNOUNCEMENT}</td>
				</tr>
				<tr>
					<td><img src="{FOLDER_LOCKED_NEW_IMG}" alt="{L_NEW_POSTS_LOCKED}" title="{L_NEW_POSTS_LOCKED}" /></td>
					<td class="gensmall">{L_NEW_POSTS_LOCKED}</td>
					<td>&nbsp;</td>
					<td><img src="{FOLDER_LOCKED_IMG}" alt="{L_NO_NEW_POSTS_LOCKED}" title="{L_NO_NEW_POSTS_LOCKED}" /></td>
					<td class="gensmall">{L_NO_NEW_POSTS_LOCKED}</td>
					<td>&nbsp;</td>
					<td><img src="{FOLDER_STICKY_IMG}" alt="{L_STICKY}" title="{L_STICKY}" /></td>
					<td class="gensmall">{L_STICKY}</td>
				</tr>
				<tr>
					<td><img src="{FOLDER_HOT_NEW_IMG}" alt="{L_NEW_POSTS_HOT}" title="{L_NEW_POSTS_HOT}" /></td>
					<td class="gensmall">{L_NEW_POSTS_HOT}</td>
					<td>&nbsp;</td>
					<td><img src="{FOLDER_HOT_IMG}" alt="{L_NO_NEW_POSTS_HOT}" title="{L_NO_NEW_POSTS_HOT}" /></td>
					<td class="gensmall">{L_NO_NEW_POSTS_HOT}</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
		<td class="row2" align="right"><span class="gensmall">{S_AUTH_LIST}</span></td>
	</tr>
</table>
