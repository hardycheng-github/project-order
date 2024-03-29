<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_php_mail.inc.php,v 1.8 2004/02/28 19:16:49 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 nextcommerce (twe_php_mail.inc.php,v 1.17 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com


   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
 function twe_php_mail($from_email_address, $from_email_name, $to_email_address, $to_name, $forwarding_to, $reply_address, $reply_address_name, $path_to_attachement, $path_to_more_attachements, $email_subject, $message_body_html, $message_body_plain) {
     global $mail_error;

$mail = new PHPMailer();
$mail->PluginDir=DIR_FS_DOCUMENT_ROOT.'includes/classes/';
if ($_SESSION['language']=='tchinese') {
	if (function_exists('iconv')) {
		$mail->CharSet="BIG5";
		$mail->SetLanguage("en",DIR_WS_CLASSES);
		$email_subject = iconv("UTF-8","BIG5//TRANSLIT",$email_subject);
		$reply_address_name = iconv("UTF-8","BIG5//TRANSLIT",$reply_address_name);
		$to_name = iconv( "UTF-8" , "BIG5//TRANSLIT" , $to_name);
		$from_email_name = iconv( "UTF-8" , "BIG5//TRANSLIT" , $from_email_name);
		$message_body_html = iconv( "UTF-8" , "BIG5//TRANSLIT" , $message_body_html);
		$message_body_plain = iconv( "UTF-8" , "BIG5//TRANSLIT" , $message_body_plain);
	} else {
		$mail->CharSet="utf-8";
		$mail->SetLanguage("en",DIR_WS_CLASSES);
		//$mail->Encoding="quoted-printable";
	}
	} else {
   $mail->CharSet=$_SESSION['language_charset'];
   $mail->SetLanguage("en",DIR_WS_CLASSES);
}
if (EMAIL_TRANSPORT=='smtp') {
$mail->IsSMTP(); 
$mail->SMTPKeepAlive = true;                                    // set mailer to use SMTP
$mail->SMTPAuth = SMTP_AUTH;                                    // turn on SMTP authentication true/false
$mail->Username = SMTP_USERNAME;                                // SMTP username
$mail->Password = SMTP_PASSWORD;                                // SMTP password
$mail->Host = SMTP_MAIN_SERVER . ';' . SMTP_Backup_Server;      // specify main and backup server "smtp1.example.com;smtp2.example.com"
$mail->Port = SMTP_PORT;
}
        
if (EMAIL_TRANSPORT=='sendmail') {                              // set mailer to use SMTP
$mail ->IsSendmail();        
$mail->Sendmail=SENDMAIL_PATH;
}
if (EMAIL_TRANSPORT=='mail') {
$mail ->IsMail();                                                                                 
}  


if ( EMAIL_USE_HTML=='true' )                                   // set email format to HTML
{
     $mail -> IsHTML(true);
     $mail->Body    = $message_body_html;
     // remove html tags
     $message_body_plain=str_replace('<br>'," \n",$message_body_plain);
     $message_body_plain=strip_tags($message_body_plain);
     $mail->AltBody = $message_body_plain;
}
else
{
     $mail -> IsHTML(false);
     //remove html tags
     $message_body_plain=str_replace('<br>'," \n",$message_body_plain);
     $message_body_plain=strip_tags($message_body_plain);
     $mail->Body    = $message_body_plain;
} 

$mail->From = $from_email_address;
$mail->FromName = $from_email_name;
$mail->AddAddress($to_email_address, $to_name);
if ($forwarding_to!='') $mail->AddBCC($forwarding_to);
$mail->AddReplyTo($reply_address, $reply_address_name);

$mail->WordWrap = 50;                                             // set word wrap to 50 characters
//$mail->AddAttachment($path_to_attachement);                     // add attachments
//$mail->AddAttachment($path_to_more_attachements);               // optional name                                          

$mail->Subject = $email_subject;


if(!$mail->Send())
{
   echo "Message was not sent <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}
}
   
?>