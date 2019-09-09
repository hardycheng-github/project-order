<?php
include('includes/configure.php');
require (DIR_WS_CLASSES . 'adodb4990/adodb-errorhandler.inc.php');
require (DIR_WS_CLASSES . 'adodb4990/adodb.inc.php');

class gift_mf  {
 // 商家的相關促銷基本資料主檔記錄  促銷方式及方案  生日、首購、來店次數 打折數 折價金額   
    var     $db;
    var     $max;
    var		$result;
    var		$sqlcmd;
    var		$cnt;
    var		$amt;    
    var		$lastdate;

    function gift_mf()
    {
        $this->db = ADONewConnection(DB_TYPE);
		if (USE_PCONNECT == 'false') {
			$this->db->Connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
		} else {
			$this->db->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
		}
    }

    function checkgift( $custid  )
    {
       $this->sqlcmd = "SELECT * FROM `gift_mf` WHERE custid = '$custid'";
       $this->result = $this->db->Execute($this->sqlcmd );
       return $this->result->RecordCount();
    }
 } 
    
class mbrpromo_mf  {
    var     $db;
    var     $max;
    var		$result;
    var		$sqlcmd;
    var		$cnt;
    var		$amt;    
    var		$lastdate;

    function mbrpromo_mf()
 // 客戶的相關促銷基本資料主檔記錄  生日、首購、來店次數   
    {
		$this->db = ADONewConnection(DB_TYPE);
		if (USE_PCONNECT == 'false') {
			$this->db->Connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
		} else {
			$this->db->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
		}
    }

    function checkt0200( $custid , $t0200  )
    {
       $this->sqlcmd = "select * from mbrpromo_mf where  custid = '$custid' and  t0200 = '$t0200'   " ;
       $this->result = $this->db->Execute( $this->sqlcmd );
       return $this->result->RecordCount();
    }

}



 // 參數傳遞 input 商家編號 custid、悠遊卡編號t0200、本次銷費總金額  discount_amount    
 //          output 、本次折扣金額  discount_amount  ( discount_total )  例: -99  及 產生promote.txt印表
// 以PHP呼叫也可以
$service_fee = false;
$custid= $_REQUEST['custid'];
$t0200= $_REQUEST['t0200'];
$discount_amount= $_REQUEST['discount_amount'];

$gift_mf_db  = new gift_mf();
$mbrpromo_mf_db  = new mbrpromo_mf();

$mmdd = date("md");
$yymmdd = date("Ymd");
$giftCount = $gift_mf_db->checkgift($custid);
$mbrCount = $mbrpromo_mf_db->checkt0200( $custid , $t0200 );
$memberInfo = $mbrpromo_mf_db->result->FetchRow();
//首次來店購物 , 自動加入會員
if ( $mbrCount == 0 ) {
	$mbrpromo_mf_db->sqlcmd = "insert into mbrpromo_mf values( '$custid' ,  '$t0200' , NULL, 'M' , 0 ,'0000000000', '$yymmdd' )  " ;
	$mbrpromo_mf_db->db->Execute( $mbrpromo_mf_db->sqlcmd );
	$mbrCount = $mbrpromo_mf_db->checkt0200( $custid , $t0200 );
	$memberInfo = $mbrpromo_mf_db->result->FetchRow();
}
//來店數自動加 1
$memberInfo["count"]++;

//讀取商家促銷資料   
$giftInfo = array();
//將折扣or服務費內容寫為xml檔
$json_content = array("discount_content"=>array());
$dis_total = 0;
$dis_once = 0;
for( $i=0; $i<$giftCount; $i++ ) {
	$giftInfo[$i] = $gift_mf_db->result->FetchRow();
	$ptype =  $giftInfo[$i]["ptype"] ;
	$promotion =  $giftInfo[$i]["promotion"] ;
	$pcode =  $giftInfo[$i]["pcode"] ;
	$bgdate =  $giftInfo[$i]["bgdate"] ;
	$exdate =  $giftInfo[$i]["exdate"] ;
	$desc1 = $giftInfo[$i]["desc1"] ;
	$desc2 = $giftInfo[$i]["desc2"] ;
	$desc3 = $giftInfo[$i]["desc3"] ;
	if ( ( $yymmdd >= $bgdate ) && ( $exdate >= $yymmdd )) {
		$ptypeCondition = false;
		switch( $ptype ){
			//設定 ptype 0 為服務費
			case "0":{
				if( $service_fee == true ){
					$ptypeCondition = true;
				}
				break;
			}
			//設定 ptype 1 為生日禮
			case "1":{
				if( $memberInfo["birthday"]  == $mmdd ){
					$ptypeCondition = true;
				}
				break;
			}
			//設定 ptype 2 為首購禮
			case "2":{
				if( $memberInfo["count"] == '1' ){
					$ptypeCondition = true;
				}
				break;
			}
			//設定 ptype 3 為來店禮
			case "3":{
				if( $giftInfo[$i]["discount3"] > 0
				 && $memberInfo["count"] >= $giftInfo[$i]["discount3"]
				 && $memberInfo["count"] % $giftInfo[$i]["discount3"] == 0 ){
					$ptypeCondition = true;
				}
				break;
			}
		}
		if ( $ptypeCondition ) {
			if  ( $pcode == "1" ) {
				$dis_once = ( $discount_amount * $giftInfo[$i]["discount1"] / 100) - $discount_amount;	
				$dis_total = $dis_total + $dis_once;
				$json_content["discount_content"][] = array("desrciption"=>$desc1, "disAmount"=>$dis_once);
			}
			if (  $pcode == "2" )  {
				$dis_once = (-1) * $giftInfo[$i]["discount2"];	  
				$dis_total =   $dis_total + $dis_once  ;
				$json_content["discount_content"][] = array("desrciption"=>$desc2, "disAmount"=>$dis_once);
			}
			if ( $pcode == "3" ) {	
				//$mbrpromo_mf_db->sqlcmd = "update  mbrpromo_mf    set count = 0 where custid = '$custid' and t0200= '$t0200' " ;
				//$mbrpromo_mf_db->db->Execute( $mbrpromo_mf_db->sqlcmd );
				$dis_once = 0;	 
				$json_content["discount_content"][] = array("desrciption"=>$desc3, "disAmount"=>$dis_once);
			}
			if ( $pcode == "4" ) {
				$dis_once = 0;
				$json_content["discount_content"][] = array("desrciption"=>$desc4, "disAmount"=>$dis_once);
			}
		}
	}  //  活動期間內    
	
}   // 商家促銷活動
if($dis_total + $discount_amount < 0) $dis_total = -$discount_amount;          
$json_content["info"] = array("amount"=>$discount_amount+$dis_total
							 ,"discount"=>$dis_total
							 ,"count"=>$memberInfo["count"]
							 ,"custid"=>$custid
							 ,"t0200"=>$t0200);
$gift_mf_db->db->Disconnect();
$mbrpromo_mf_db->db->Disconnect();
echo json_encode($json_content);
exit(1);
?>
