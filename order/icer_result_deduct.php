<?php
require("includes/application_top.php");
require_once(DIR_FS_INC . 'icer_function.php');

if(file_exists($icer_res_ok)) unlink($icer_res_ok);
if(!isset($_GET['timeout'])){
	$result = readTransXML($icer_res);
}
else{
	$result = array();
}
$link = twe_href_link(FILENAME_DEFAULT);
$msg = "";
$success = false;
if(isset($result['T3901']) && $result['T3901'] == 0){
	$_SESSION['payment'] = "easycard";
	$img = $icer_img_success;
	$deduct = $result['T0400'] / 100;
	$money = $result['T0410'] / 100;
	if(isset($result['T0409']) && $result['T0409'] > 0){
		$auto_add = $result['T0409'] / 100;
		$msg .= "<tr><td align='center'><h1>本次交易金額:".$deduct."<br>卡片餘額:".$money."<br>自動加值金額:".$auto_add."</h1></td></tr>";
	}
	else{
		$msg .= "<tr><td align='center'><h1>本次交易金額:".$deduct."<br>卡片餘額:".$money."</h1></td></tr>";
	}
	$link = twe_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
	$success = true;
	//紀錄來店次數
	
}
else if(isset($result['T3901']) && $result['T3901'] == '-123') $img = $icer_img_insufficient;
else if(isset($result['T3901']) && $result['T3901'] == '-119' && isset($result['T3904']) && $result['T3904'] == '6201') $img = $icer_img_timeout;
else $img = $icer_img_error;
//retry start
$retry_count = 0;
if(isset($_GET['retry']) && is_numeric($_GET['retry'])) $retry_count = $_GET['retry'];
//retry condition
if((isset($result['T3901']) && $result['T3901'] == '-125') || (isset($_GET['timeout'])) ||
( $retry_count > 0 && isset($result['T3901']) && $result['T3901'] == '-119' && isset($result['T3904']) && preg_match("/^62..$/",$result['T3904']))){
	$result['retry'] = $retry_count;
	$retry_count++;
	if($retry_count <= 3) $link = $icer_deduct . "?retry=" . $retry_count;
}
//retry end
if($success == false){
	foreach($icer_msg_table as $icer_msg){
		$condition = true;
		foreach($icer_msg['code'] as $code=>$val){
			if((isset($result[$code]) && preg_match("/^".$val."$/",$result[$code])) == false){
				$condition = false;
				break;
			}
		}
		if($condition){
			$tmpMsg = $icer_msg['msg'];
			foreach($icer_code_list as $code){
				if(isset($result[$code])){
					$tmpMsg = str_replace('{'.$code.'}',$result[$code],$tmpMsg);
				}
			}
			$msg .= "<tr><td align='center'><h1>" . $tmpMsg . "</h1></td></tr>";
		}
	}
	if(isset($result['retry']) && $retry_count <= 3) $msg .= "<tr><td align='center'><a href='".$link."'><img src=".$icer_img_retry." /></a></td></tr>";
	else $msg .= "<tr><td align='center'><a href='".$link."'>".twe_image_button('button_home.png')."</a></td></tr>";
}
else{
	header("refresh:2; url=".$link);
}
//header("refresh:2; url=".$link);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
	</head>
	<body>
		<div style="height:auto; width:auto; position:fixed; top:0; left:0; bottom:0; right:0; z-index:99;">
		<table style="width:100%; height:100%; vertical-align: middle; align: center;"><tr><td>
			<table align="center">
				<tr><td align="center"><img src=<?php echo $img;?> /></td></tr>
				<?php if(isset($msg) && $msg) echo $msg;?>
			</table>
		</td></tr></table></div>
	</body>
</html>