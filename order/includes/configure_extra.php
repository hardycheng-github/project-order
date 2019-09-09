<?php
// 額外的設定
	define('option_colNum', 5);
	$custid = '5DRAGON';
	$seller = '12345678';
	$default_login = true; //使用預設帳密登入
	//$default_account = 'joy@twra.com.tw';
	$default_account = 'anonymous@anonymous.com';
	$default_password= '123456';
	$default_categories_name_size = 24;
	define('show_top_marquee', true); //是否顯示LOGO下方的跑馬燈
	define('show_breadcrumb', false); //是否顯示導覽列
	define('block_height', 1050);
	define('cartbar_height', 100);
	define('marquee_height', 50);
	define('frame_height', block_height+cartbar_height);
	$order_clear=false; //刪除order檔案
	$button_next_disable_style='style="opacity:0.3;"';
	$bottom_height=500;
	$bottom_btn_height=100;
	$bottom_list_height=$bottom_height-$bottom_btn_height;
	$product_height=256;
	$product_width=256;
	$product_number_selector="<input type='number' name='products_qty' value='1' min='1' max='999'>";
	$product_number_selector_cart="<input class='qtyCtl_text' type='text' name='cart_quantity[]' value='1' min='1' max='999'>";
	$skip_checkout = true; //是否跳過結帳步驟
	$shipping_value='selfpickup_selfpickup'; //送貨方式:自取
	$print_cgi_url='http://localhost/cgi-bin/print.cgi';
	define('DIR_FS_ORDER_XML', DIR_FS_DOCUMENT_ROOT.'order/');
	$timeout_secs = 30;
	//icer
	$root_link = HTTP_SERVER . "/";
	$promoopr_url = $root_link."promoopr.php";
	$root_file = DIR_FS_DOCUMENT_ROOT;
	$icer_deduct = $root_link . "icer_deduct.php";
	$icer_cardNoQuery = $root_link . "icer_cardNoQuery.php";
	$icer_result_deduct = $root_link . "icer_result_deduct.php";
	$icer_result_cardNoQuery = $root_link . "icer_result_cardNoQuery.php";
	$icer_path = $root_file;
	$icer_api_jar = $icer_path . "icer4j-1.2.3P.jar";
	$icer_api_ftp = $icer_path . "icerFtp-0.0.1.jar";
	$icer_api_cmd = "java -DBASE_PATH=".$icer_path." -jar ".$icer_api_jar;
	$icer_api_cardNoQuery = $icer_api_cmd . " cardNoQuery";
	$icer_api_deduct = $icer_api_cmd . " deduct";
	$icer_api_signOn = $icer_api_cmd . " signOn";
	$icer_api_settle = $icer_api_cmd . " settle";
	$icer_api_retry = $icer_api_cmd . " retry";
	$icer_data = $icer_path . "ICERData/";
	$icer_sample = $root_file . "sample/";
	$icer_req_ok = $icer_data . "ICERAPI.REQ.OK";
	$icer_req = $icer_data . "ICERAPI.REQ";
	$icer_res_ok = $icer_data . "ICERAPI.RES.OK";
	$icer_over = $icer_data . "ICERAPI.OVER";
	$icer_url_res_ok = $root_link . "ICERData/ICERAPI.RES.OK";
	$icer_res = $icer_data . "ICERAPI.RES";
	$icer_img = $root_link . "images/icer_images/";
	$icer_img_loading = $icer_img . "loading.gif";
	$icer_img_success = $icer_img . "notice_success.png";
	$icer_img_insufficient = $icer_img . "notice_fail1.png";
	$icer_img_timeout = $icer_img . "notice_fail2.png";
	$icer_img_error = $icer_img . "notice_fail0.png";
	$icer_img_home = $icer_img . "home.png";
	$icer_img_retry = $icer_img . "retry.png";
	$icer_text_induction = '請持續感應票卡';
	$icer_text_induction_retry = '重試中，請持續感應票卡';
	$icer_code_list = array('T3900','T3901','T3903','T3904','T3908','T3909');
	$logo_path = DIR_WS_IMAGES . 'logo.png';
	$banner_path = DIR_WS_IMAGES . 'banner/bottom_banner.gif';
	require_once(DIR_WS_INCLUDES . 'configure_icerList.php');
	if(file_exists($icer_res_ok)) unlink($icer_res_ok);
	//移除特定get參數
	function removeGetVar($url, $name) {
		$url = preg_replace('/([?&])'.$name.'=[^&]+(&|$)/','$1',$url);
		if(substr($url, -1) == '?' || substr($url, -1) == '&') $url = substr($url, 0, strlen($url)-1);
		return preg_replace('/([?&])'.$name.'=[^&]+(&|$)/','$1',$url);
	}
	//加入get參數
	function addGetVar($url, $name, $value) {
		$url = removeGetVar($url, $name);
		return $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . "$name=$value";
	}
	//統一編號檢查，true/false
	//https://github.com/kiang/localized/blob/master/libs/tw_validation.php
	function ubnCheck($check) {
		if (!preg_match('/^[0-9]{8}$/', $check)) {
			return false;
		}
		$tbNum = array(1,2,1,2,1,2,4,1);
		$intSum = 0;
		for ($i = 0; $i < 8; $i++) {
			$intMultiply = $check[$i] * $tbNum[$i];
			$intAddition = (floor($intMultiply / 10) + ($intMultiply % 10));
			$intSum += $intAddition;
		}
		return ($intSum % 10 == 0) || ($intSum % 10 == 9 && $check[6] == 7);
	}

?>
