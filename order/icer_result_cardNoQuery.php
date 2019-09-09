<?php
require("includes/application_top.php");
require_once(DIR_FS_INC . 'icer_function.php');

unset($_SESSION['discount_content']);

if(file_exists($icer_res_ok)) unlink($icer_res_ok);
if(!isset($_GET['timeout'])){
	$result = readTransXML($icer_res);
}
else{
	$result = array();
}
$deduct = 0;
if(isset($_GET['deduct']) && is_numeric($_GET['deduct'])){
	$deduct = $_GET['deduct'];
}
$link = twe_href_link(FILENAME_DEFAULT);
$msg = "";
$success = false;

if(isset($result['T3901']) && $result['T3901'] == 0){
	//discount
	$url = $promoopr_url;
	$url = addGetVar($url, 'custid', $custid);
	$url = addGetVar($url, 't0200', $result['T0200']);
	$url = addGetVar($url, 'discount_amount', $deduct);
	//echo $url."<br>";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true); // 不直接出現回傳值
	$response = curl_exec($ch);
	$response = preg_replace("/\xEF\xBB\xBF/", "", $response); //&#65279; replace
	$content = json_decode($response, true);
	$_SESSION['discount_content'] = $content;
	//print_r($content);
	//print_r(curl_getinfo($ch));
	curl_close($ch);
	//discount end
	$img = $icer_img_success;
	$deduct = $content['info']['amount'];
	$msg .= "<tr><td align='center'><h1>本次交易金額:".$deduct."<br>";
	$msg .= "折扣總金額:".$content['info']['discount']."<br>";
	$msg .= "折扣後金額:".$content['info']['amount']."<br>";
	$msg .= "來店次數:".$content['info']['count']."</h1></td></tr>";
	$link = $icer_deduct . "?deduct=" . $deduct;
	$success = true;
	$check = "<a href=$link>".twe_image_button('button_check.png')."</a>";
	$cancel = "<a href=".twe_href_link(FILENAME_DEFAULT).">".twe_image_button('button_cancel.png')."</a>";
	//紀錄來店次數
	foreach($content['discount_content'] as $discount_count){
		$msg .= "<tr><td align='center'><h3>[";
		$msg .= $discount_count['desrciption'];
		if($discount_count['disAmount'] != 0) $msg .= ":".$discount_count['disAmount'];
		$msg .= "]</h3></td></tr>";
	}
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
	$msg .= "<tr><td align='center'>$cancel&nbsp$check</td></tr>";
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
				<tr hidden><td align="center"><img src=<?php echo $img;?> /></td></tr>
				<?php if(isset($msg) && $msg) echo $msg;?>
			</table>
		</td></tr></table></div>
	</body>
</html>