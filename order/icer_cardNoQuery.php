<?php
require("includes/application_top.php");
require_once(DIR_FS_INC . 'icer_function.php');

// initialize smarty
$smarty = new Smarty;

$induction_msg = $icer_text_induction;
$deduct = 0;
if(isset($_GET['deduct']) && is_numeric($_GET['deduct'])){
	$deduct = $_GET['deduct'];
}
$retry = 0;
if(isset($_GET['retry']) && is_numeric($_GET['retry'])){
	$induction_msg = $icer_text_induction_retry;
	$retry = $_GET['retry'];
	$next_url = $icer_result_cardNoQuery."?retry=".$retry;
	header( "refresh:".$timeout_secs."; url=".$icer_result_cardNoQuery."?timeout=1&retry=".$retry);
}
else{
	$next_url = $icer_result_cardNoQuery."?deduct=".$deduct;
	header( "refresh:".$timeout_secs."; url=".$icer_result_cardNoQuery."?timeout=1");
}
if($retry == 0){
	$cmd = $icer_api_cardNoQuery;
}
else{
	$cmd = $icer_api_retry;
}
execInBackground($cmd);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
		<script type="text/javascript">
			function urlExists(url)
			{
				var http = new XMLHttpRequest();
				http.open('HEAD', url, false);
				http.send();
				return http.status!=404;
			}
			function check()
			{
				if ( urlExists('<?php echo $icer_url_res_ok?>') == 1){
					window.location.replace("<?php echo $next_url;?>");//replace:no backstep
				}
			}
			setInterval(check, 100);
		</script>
	</head>
	<body>
		<div style="height:auto; width:auto; position:fixed; top:0; left:0; bottom:0; right:0; z-index:99;">
		<table style="width:100%; height:100%; vertical-align: middle; align: center;"><tr><td>
			<table align="center">
				<tr><td align="center"><h1><?php echo $induction_msg;?></h1></td></tr>
				<tr><td align="center"><img src=<?php echo $icer_img_loading;?> /></td></tr>
				<tr><td align='center'><a href='<?php echo twe_href_link(FILENAME_DEFAULT);?>'><img src='<?php echo $icer_img_home;?>'/></a></td></tr>
			</table>
		</td></tr></table></div>
	</body>
</html>