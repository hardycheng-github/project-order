<script language="javascript" type="text/javascript">
<!--
function emoticon(text) {
	text = ' ' + text + ' ';
	if (opener.document.forms['post'].message.createTextRange && opener.document.forms['post'].message.caretPos) {
		var caretPos = opener.document.forms['post'].message.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		opener.document.forms['post'].message.focus();
	} else {
	opener.document.forms['post'].message.value  += text;
	opener.document.forms['post'].message.focus();
	}
}
//-->
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
<tr>
<td>
 <table width="100%" border="0" cellspacing="1" cellpadding="4" class="forumline">
<tr>
<th>{L_EMOTICONS}</th>
</tr>
<tr>
<td class="row1">
<table width="100" border="0" cellspacing="0" cellpadding="5">
<!-- BEGIN smilies_row -->
<tr align="center">
<!-- BEGIN smilies_col -->
<td>
<a href="javascript:emoticon('{smilies_row.smilies_col.SMILEY_CODE}')"><img src="{smilies_row.smilies_col.SMILEY_IMG}" alt="{smilies_row.smilies_col.SMILEY_DESC}" width="15" height="15" border="0" title="{smilies_row.smilies_col.SMILEY_DESC}" /></a></td>
<!-- END smilies_col -->
</tr>
<!-- END smilies_row -->
<!-- BEGIN switch_smilies_extra -->
<tr align="center">
<td class="nav" colspan="{S_SMILIES_COLSPAN}"><a href="{U_MORE_SMILIES}" onclick="open_window('{U_MORE_SMILIES}', 250, 300);return false" target="_smilies">{L_MORE_SMILIES}</a>
</td>
</tr>
<!-- END switch_smilies_extra -->
</table>
</td>
</tr>
<tr>
<td class="row2" align="center"> <a class="genmed" href="javascript:window.close();">{L_CLOSE_WINDOW}</a>
</td>
</tr>
</table>
 <table border="0" cellpadding="0" cellspacing="0" class="ttb">
 	<tr>
 		<td><img src="templates/fiapplegreen/images/tb12_l.gif" width="10" height="12" alt="" /></td>
 		<td class="tb12bkg"><img src="images/spacer.gif" width="200" height="12" alt="" /></td>
 		<td><img src="templates/fiapplegreen/images/tb12_r.gif" width="10" height="12" alt="" /></td>
 		</tr>
 	</table>
 </td>
</tr>
</table>
