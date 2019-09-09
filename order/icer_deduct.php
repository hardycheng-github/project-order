<?php
require("includes/application_top.php");
require_once(DIR_FS_INC . 'icer_function.php');
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
	$next_url = $icer_result_deduct."?retry=".$retry;
	$timeout_url = $icer_result_deduct."?timeout=1&retry=".$retry;
}
else{
	$next_url = $icer_result_deduct."?deduct=".$deduct;
	$timeout_url = $icer_result_deduct."?timeout=1";
}
if($retry == 0){
	$cmd = $icer_api_deduct . " " .$deduct;
}
else{
	$cmd = $icer_api_retry;
}
execInBackground($cmd);

require_once(DIR_WS_INCLUDES . 'header.php');
$smarty->assign('icer_url_res_ok', $icer_url_res_ok);
$smarty->assign('next_url', $next_url);
$smarty->assign('timout_url', $timeout_url);
$smarty->assign('induction_msg', $induction_msg);
$smarty->assign('icer_img_loading', twe_image_button('icer_loading.gif'));
$smarty->assign('button_home', '<a href="'.twe_href_link(FILENAME_DEFAULT).'">'.twe_image_button('button_home.png').'</a>');
$smarty->assign('timeout_secs', $timeout_secs);

$smarty->assign('language', $_SESSION['language']);
$smarty->caching = 0;
$smarty->display(CURRENT_TEMPLATE . '/icer/icer_induction.html');
?>