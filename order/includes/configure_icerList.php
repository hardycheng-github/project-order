<?php
$icer_msg_table = array(
	array('code' => array('retry'=>'0'),'msg' => '請確認卡片放置正確後進行重試'),
	array('code' => array('retry'=>'1'),'msg' => '請確認卡片放置正確後進行重試'),
	array('code' => array('retry'=>'2'),'msg' => '請確認卡片放置正確後進行重試'),
	array('code' => array('retry'=>'3'),'msg' => '重試已達3次，請確認卡片餘額'),
	array('code' => array('T3900'=>'03'),'msg' => '[{T3900}] 不合法的店號'),
	array('code' => array('T3900'=>'04'),'msg' => '[{T3900}] 票卡異常'),
	array('code' => array('T3900'=>'05'),'msg' => '[{T3900}] 票卡異常'),
	array('code' => array('T3900'=>'06'),'msg' => '錯誤代碼：{T3900}+{T3909}+{T3903}'),
	array('code' => array('T3900'=>'12'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'13'),'msg' => '[{T3900}] 金額不合法'),
	array('code' => array('T3900'=>'14'),'msg' => '[{T3900}] 卡號不合法'),
	array('code' => array('T3900'=>'15'),'msg' => '[{T3900}] 票卡異常'),
	array('code' => array('T3900'=>'19'),'msg' => '[{T3900}] 交易重複'),
	array('code' => array('T3900'=>'25'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'30'),'msg' => '[{T3900}] 格式錯誤'),
	array('code' => array('T3900'=>'41'),'msg' => '[{T3900}] 請持卡人與發卡銀行確認'),
	array('code' => array('T3900'=>'51'),'msg' => '[{T3900}] 額度不足'),
	array('code' => array('T3900'=>'54'),'msg' => '[{T3900}] 卡片過期'),
	array('code' => array('T3900'=>'57'),'msg' => '[{T3900}] 請持卡人與發卡銀行確認'),
	array('code' => array('T3900'=>'58'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'59'),'msg' => '[{T3900}] 端末設備不合法'),
	array('code' => array('T3900'=>'60'),'msg' => '[{T3900}] 配對失敗'),
	array('code' => array('T3900'=>'61'),'msg' => '[{T3900}] 超過金額上限'),
	array('code' => array('T3900'=>'63'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'76'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'77'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'96'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'97'),'msg' => '[{T3900}] 請門市人員報修'),
	array('code' => array('T3900'=>'98'),'msg' => '[{T3900}] 請門市人員報修'),
	//array('code' => array('T3901'=>'-101'),'msg' => 'CMAS主機回應+T3900畫面呈現'),
	//array('code' => array('T3901'=>'-102'),'msg' => 'EZHOST主機回應+T3902畫面呈現'),
	array('code' => array('T3901'=>'-103'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-104'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-105'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-106'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-109'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-110'),'msg' => '[{T3901}] 網路傳送逾時'),
	array('code' => array('T3901'=>'-111'),'msg' => '[{T3901}] 網路接收逾時'),
	array('code' => array('T3901'=>'-117'),'msg' => '[{T3901}] 前次交易尚未結帳，需先結帳'),
	array('code' => array('T3901'=>'-118'),'msg' => '[{T3901}] 請門市人員報修'),
	//array('code' => array('T3901'=>'-119'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-120'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-121'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-122'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-123'),'msg' => '[{T3901}] 餘額不足'),
	array('code' => array('T3901'=>'-124'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-125'),'msg' => '[{T3901}] 感應不良，請重試，請勿移動卡片'),
	array('code' => array('T3901'=>'-126'),'msg' => '[{T3901}] 非Retry交易'),
	array('code' => array('T3901'=>'-128'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-130'),'msg' => '[{T3901}] 票卡已退卡'),
	array('code' => array('T3901'=>'-131'),'msg' => '[{T3901}] 卡別錯誤'),
	array('code' => array('T3901'=>'-132'),'msg' => '[{T3901}] 票卡異常'),
	array('code' => array('T3901'=>'-133'),'msg' => '[{T3901}] 票卡已鎖'),
	array('code' => array('T3901'=>'-134'),'msg' => '[{T3901}] 未開卡之票卡'),
	array('code' => array('T3901'=>'-136'),'msg' => '[{T3901}] 請門市人員報修'),
	//array('code' => array('T3901'=>'-138'),'msg' => 'T3908畫面呈現'),
	array('code' => array('T3901'=>'-139'),'msg' => '[{T3901}] 學生票已展期,不需展期;帳戶連結已設定,不需設定'),
	array('code' => array('T3901'=>'-1'),'msg' => '[{T3901}] Comport開啟失敗'),
	array('code' => array('T3901'=>'-2'),'msg' => '[{T3901}] Comport開啟失敗'),
	array('code' => array('T3901'=>'-3'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-4'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-5'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-6'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-7'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-8'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-9'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-11'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-18'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-19'),'msg' => '[{T3901}] Comport接收失敗'),
	array('code' => array('T3901'=>'-21'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-22'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-23'),'msg' => '[{T3901}] Log檔案開啟失敗'),
	array('code' => array('T3901'=>'-32'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-34'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3901'=>'-36'),'msg' => '[{T3901}] 請門市人員報修'),
	array('code' => array('T3903'=>'04'),'msg' => '[{T3903}] 票卡異常'),
	array('code' => array('T3903'=>'05'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'12'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'13'),'msg' => '[{T3903}] 請確認交易金額'),
	array('code' => array('T3903'=>'14'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'15'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'19'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'30'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'51'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'54'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'55'),'msg' => '[{T3903}] 無需展期'),
	array('code' => array('T3903'=>'57'),'msg' => '[{T3903}] 請與發卡銀行確認'),
	array('code' => array('T3903'=>'58'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'59'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'61'),'msg' => '[{T3903}] 超過金額上限'),
	array('code' => array('T3903'=>'65'),'msg' => '[{T3903}] 超過次數上限,'),
	array('code' => array('T3903'=>'76'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'77'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'90'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'91'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'92'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'93'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'94'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'96'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'97'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'98'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'99'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3903'=>'N3'),'msg' => '[{T3903}] 請門市人員報修'),
	array('code' => array('T3904'=>'6001'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6002'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6003'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6004'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6005'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6088'),'msg' => '[{T3904}] 線路不良/Time Out，請進行Retry'),
	array('code' => array('T3904'=>'60A1'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'60A2'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6101'),'msg' => '[{T3904}] 票卡不適用'),
	array('code' => array('T3904'=>'6102'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6103'),'msg' => '[{T3904}] 票卡CPD Error'),
	array('code' => array('T3904'=>'6104'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6105'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6106'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6107'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6108'),'msg' => '[{T3904}] 票卡過期'),
	array('code' => array('T3904'=>'6109'),'msg' => '[{T3904}] 票卡已鎖卡'),
	array('code' => array('T3904'=>'610A'),'msg' => '[{T3904}] 票卡內檢核碼錯誤'),
	array('code' => array('T3904'=>'610B'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'610C'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'610D'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'610E'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'610F'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6111'),'msg' => '[{T3904}] 票卡異常'),
	array('code' => array('T3904'=>'6201'),'msg' => '[{T3904}] 找不到卡片，請重新操作'),
	array('code' => array('T3904'=>'6202'),'msg' => '[{T3904}] 讀卡失敗，請重新操作'),
	array('code' => array('T3904'=>'6203'),'msg' => '[{T3904}] 寫卡失敗，請重新操作'),
	array('code' => array('T3904'=>'6204'),'msg' => '[{T3904}] 多張卡，請重新操作'),
	array('code' => array('T3904'=>'6205'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6206'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6207'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6208'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6301'),'msg' => '[{T3904}] 請確認卡機版本'),
	array('code' => array('T3904'=>'6302'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6303'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6304'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6305'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6306'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6307'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6308'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6309'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'630A'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'630B'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'630C'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6401'),'msg' => '[{T3904}]確認是否為上一筆交易之卡片'),
	array('code' => array('T3904'=>'6402'),'msg' => '[{T3904}]確認交易金額及餘額'),
	array('code' => array('T3904'=>'6403'),'msg' => '[{T3904}]餘額不足請加值'),
	array('code' => array('T3904'=>'6404'),'msg' => '[{T3904}]卡號錯誤'),
	array('code' => array('T3904'=>'6406'),'msg' => '[{T3904}] 交易異常'),
	array('code' => array('T3904'=>'6409'),'msg' => '[{T3904}] 交易異常'),
	array('code' => array('T3904'=>'640A'),'msg' => '[{T3904}] 交易異常'),
	array('code' => array('T3904'=>'640B'),'msg' => '[{T3904}] 交易異常'),
	array('code' => array('T3904'=>'640C'),'msg' => '[{T3904}] 超過交易日限額'),
	array('code' => array('T3904'=>'640D'),'msg' => '[{T3904}] 超過交易次限額'),
	array('code' => array('T3904'=>'640E'),'msg' => '[{T3904}] 餘額異常'),
	array('code' => array('T3904'=>'640F'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6410'),'msg' => '[{T3904}]該卡不適用於此交易'),
	array('code' => array('T3904'=>'6411'),'msg' => '[{T3904}]該卡不適用於此交易'),
	array('code' => array('T3904'=>'6412'),'msg' => '[{T3904}]該卡不適用於此交易'),
	array('code' => array('T3904'=>'6413'),'msg' => '[{T3904}]該卡不適用於此交易'),
	array('code' => array('T3904'=>'6414'),'msg' => '[{T3904}]該卡不適用於此交易'),
	array('code' => array('T3904'=>'6415'),'msg' => '[{T3904}]須執行交易合法驗證'),
	array('code' => array('T3904'=>'6416'),'msg' => '[{T3904}]自動加值功能已開啟,不需再次開啟'),
	array('code' => array('T3904'=>'6417'),'msg' => '[{T3904}]該卡不適用於此交易'),
	array('code' => array('T3904'=>'6418'),'msg' => '[{T3904}]票卡於此通路限制使用'),
	array('code' => array('T3904'=>'6419'),'msg' => '[{T3904}] 該卡不適用於此交易'),
	array('code' => array('T3904'=>'641A'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'641B'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'641C'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6501'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6502'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6503'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6504'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6505'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6506'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6507'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6508'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6509'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6522'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'65FF'),'msg' => '[{T3904}] 安全錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6602'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6604'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6608'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6610'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6612'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6620'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6624'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6625'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6640'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6650'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'667C'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'667F'),'msg' => '[{T3904}] 卡機錯誤請門市人員報修'),
	array('code' => array('T3904'=>'6700'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6A80'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6B00'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6D00'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6E00'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3904'=>'6F00'),'msg' => '[{T3904}] 請門市人員報修'),
	array('code' => array('T3908'=>'.+'),'msg' => '請參照各銀行規格'),
	array('code' => array('T3909'=>'.+'),'msg' => '主機代碼：{T3909}'),
	array('code' => array('T3909'=>'.+'),'msg' => '主機代碼：{T3909}'),
	array('code' => array('T3909'=>'.+'),'msg' => '主機代碼：{T3909}')
);
?>