<?php
/* --------------------------------------------------------------
   $Id: module_newsletter.php,v 1.14 2005/01/23 20:06:15 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercecoding standards www.oscommerce.com 
   (c) 2003	 nextcommerce (templates_boxes.php,v 1.14 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');




  switch ($_GET['action']) {  // actions for datahandling

  	case 'save': // save newsletter

     $newsletter_title=twe_db_prepare_input($_POST['title']);
     $body=twe_db_prepare_input($_POST['newsletter_body']);
     $id=twe_db_prepare_input((int)$_POST['ID']);
     $status_all=twe_db_prepare_input($_POST['status_all']);
     if ($newsletter_title=='') $newsletter_title='no title';
     $customers_status=twe_get_customers_statuses();
     $rzp='';
     for ($i=0,$n=sizeof($customers_status);$i<$n; $i++) {

     if (twe_db_prepare_input($_POST['status'][$i])=='yes') {
         if ($rzp!='') $rzp.=',';
     $rzp.=$customers_status[$i]['id'];
     }


     }
      if (twe_db_prepare_input($_POST['status_all'])=='yes') $rzp.=',all';
     $error=false; // reset error flag

      if ($error == false) {

      $sql_data_array = array('title'=> $newsletter_title,
                               'status' => '0',
                               'bc'=>$rzp,
                               'date' => 'now()',
                               'body' => $body);

   if ($id!='') {
   twe_db_perform(TABLE_MODULE_NEWSLETTER, $sql_data_array, 'update', "newsletter_id = '" . $id . "'");
   // create temp table
   $db->Execute("DROP TABLE IF EXISTS module_newsletter_temp_".$id);
   $db->Execute("CREATE TABLE module_newsletter_temp_".$id."
                  (
                     id int(11) NOT NULL auto_increment,
                    customers_id int(11) NOT NULL default '0',
                    customers_status int(11) NOT NULL default '0',
                    customers_firstname varchar(64) NOT NULL default '',
                    customers_lastname varchar(64) NOT NULL default '',
                    customers_email_address text NOT NULL,
                    date datetime NOT NULL default '0000-00-00 00:00:00',
                    comment varchar(64) NOT NULL default '',
                    PRIMARY KEY  (id)
                    )");
   } else {
   twe_db_perform(TABLE_MODULE_NEWSLETTER, $sql_data_array);
   // create temp table
   $id=$db->Insert_ID();
   $db->Execute("DROP TABLE IF EXISTS module_newsletter_temp_".$id);
   $db->Execute("CREATE TABLE module_newsletter_temp_".$id."
                  (
                     id int(11) NOT NULL auto_increment,
                    customers_id int(11) NOT NULL default '0',
                    customers_status int(11) NOT NULL default '0',
                    customers_firstname varchar(64) NOT NULL default '',
                    customers_lastname varchar(64) NOT NULL default '',
                    customers_email_address text NOT NULL,
                    date datetime NOT NULL default '0000-00-00 00:00:00',
                    comment varchar(64) NOT NULL default '',
                    PRIMARY KEY  (id)
                    )");
   }

   // filling temp table with data!
   $flag='';
   if (!strpos($rzp,'all')) $flag='true';
   $rzp=str_replace(',all','',$rzp);
   $groups=explode(',',$rzp);
   $sql_data_array='';

   for ($i=0,$n=sizeof($groups);$i<$n;$i++) {
   // check if cusomer want newsletter
   if ($status_all=='yes') {
   $customers_data=$db->Execute("SELECT
                                  customers_id,
                                  customers_firstname,
                                  customers_lastname,
                                  customers_email_address
                                  FROM ".TABLE_CUSTOMERS."
                                  WHERE
                                  customers_status='".$groups[$i]."'");
   } else {
      $customers_data=$db->Execute("SELECT
                                  customers_id,
                                  customers_firstname,
                                  customers_lastname,
                                  customers_email_address
                                  FROM ".TABLE_CUSTOMERS."
                                  WHERE
                                  customers_status='".$groups[$i]."' and
                                  customers_newsletter='1'");
   }
   while (!$customers_data->EOF){
          $sql_data_array=array(
                               'customers_id'=>$customers_data->fields['customers_id'],
                               'customers_status'=>$groups[$i],
                               'customers_firstname'=>$customers_data->fields['customers_firstname'],
                               'customers_lastname'=>$customers_data->fields['customers_lastname'],
                               'customers_email_address'=>$customers_data->fields['customers_email_address'],
                               'date'=>'now()');


   twe_db_perform('module_newsletter_temp_'.$id, $sql_data_array);
    $customers_data->MoveNext(); 
   }


   }

   twe_redirect(twe_href_link(FILENAME_MODULE_NEWSLETTER));
   }

   break;

   case 'delete':

   $db->Execute("DELETE FROM ".TABLE_MODULE_NEWSLETTER." WHERE   newsletter_id='".(int)$_GET['ID']."'");
   twe_redirect(twe_href_link(FILENAME_MODULE_NEWSLETTER));

   break;

   case 'send':
   // max email package  -> should be in admin area!
   $package_size='30';
   twe_redirect(twe_href_link(FILENAME_MODULE_NEWSLETTER,'send=0,'.$package_size.'&ID='.(int)$_GET['ID']));
   }

// action for sending mails!

if ($_GET['send']) {

$limits=explode(',',$_GET['send']);
$limit_low = $limits['0'];
$limit_up = $limits['1'];



     $limit_query="SELECT count(*) as count
                                FROM module_newsletter_temp_".(int)$_GET['ID']."
                                ";
     $limit_data=$db->Execute($limit_query);



 // select emailrange from db

 	$email_query_data=$db->Execute("SELECT
                               customers_firstname,
                               customers_lastname,
                               customers_email_address,
                               id
                               FROM  module_newsletter_temp_".(int)$_GET['ID']."
                               LIMIT ".$limit_low.",".$limit_up);

     $email_data=array();
 while (!$email_query_data->EOF) {

 $email_data[]=array('id' => $email_query_data->fields['id'],
                      'firstname'=>$email_query_data->fields['customers_firstname'],
                        'lastname'=>$email_query_data->fields['customers_lastname'],
                        'email'=>$email_query_data->fields['customers_email_address']);
$email_query_data->MoveNext();
 }

 // ok lets send the mails in package of 30 mails, to prevent php timeout
 $package_size='30';
 $break='0';
 if ($limit_data->fields['count']<$limit_up) {
     $limit_up=$limit_data->fields['count'];
     $break='1';
 }
 $max_runtime=$limit_up-$limit_low;
  $newsletters_query="SELECT
                                   title,
                                    body,
                                    bc,
                                    cc
                                  FROM ".TABLE_MODULE_NEWSLETTER."
                                  WHERE  newsletter_id='".(int)$_GET['ID']."'";
 $newsletters_data=$db->Execute($newsletters_query);
 /*if ($newsletters_data->fields['cc']!='') {
 twe_php_mail(EMAIL_SUPPORT_ADDRESS,
              EMAIL_SUPPORT_NAME,
              $newsletters_data->fields['cc'],
              '' ,
              '',
              EMAIL_SUPPORT_REPLY_ADDRESS,
              EMAIL_SUPPORT_REPLY_ADDRESS_NAME,
              '',
              '',
              $newsletters_data->fields['title'],
              $newsletters_data->fields['body'],
              $newsletters_data->fields['body']);

              }*/

 for ($i=1;$i<=$max_runtime;$i++)
 {
  // mail
  twe_php_mail(EMAIL_SUPPORT_ADDRESS,
               EMAIL_SUPPORT_NAME,
               $email_data[$i-1]['email'] ,
               $email_data[$i-1]['lastname'] . ' ' . $email_data[$i-1]['firstname'] ,
               '',
               EMAIL_SUPPORT_REPLY_ADDRESS,
               EMAIL_SUPPORT_REPLY_ADDRESS_NAME,
                '',
                '',
                $newsletters_data->fields['title'],
                $newsletters_data->fields['body'] ,
                $newsletters_data->fields['body']);

  $db->Execute("UPDATE module_newsletter_temp_".(int)$_GET['ID']." SET comment='send' WHERE id='".$email_data[$i-1]['id']."'");

 }
 if ($break=='1') {
     // finished

          $limit1_query="SELECT count(*) as count
                                FROM module_newsletter_temp_".(int)$_GET['ID']."
                                WHERE comment='send'";
     $limit1_data=$db->Execute($limit1_query);

     if ($limit1_data->fields['count']-$limit_data->fields['count']<=0)
     {
     $db->Execute("UPDATE ".TABLE_MODULE_NEWSLETTER." SET status='1' WHERE newsletter_id='".(int)$_GET['ID']."'");
     twe_redirect(twe_href_link(FILENAME_MODULE_NEWSLETTER));
     } else {
     echo '<b>'.$limit1_data->fields['count'].'<b> emails send<br>';
     echo '<b>'.$limit1_data->fields['count']-$limit_data->fields['count'].'<b> emails left';
     }


 } else {
 $limit_low=$limit_up+1;
 $limit_up=$limit_low+$package_size;
 twe_redirect(twe_href_link(FILENAME_MODULE_NEWSLETTER,'send='.$limit_low.','.$limit_up.'&ID='.(int)$_GET['ID']));
 }


}


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php if (USE_SPAW=='true') {
 $query="SELECT code FROM ". TABLE_LANGUAGES ." WHERE languages_id='".$_SESSION['languages_id']."'";
 $data=$db->Execute($query);
 ?>
<script type="text/javascript">
   _editor_url = "includes/htmlarea/";
   _editor_lang = "<?php echo $data->fields['code']; ?>";
</script>
    <!-- DWD Modify -> Add: HTMLArea v3.0 !-->
    <!-- Load HTMLArea Core Files. !-->
<script type="text/javascript" src="includes/htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="includes/htmlarea/dialog.js"></script>
<script tyle="text/javascript" src="includes/htmlarea/lang/<?php echo $data->fields['code']; ?>.js"></script>
<?php } ?>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" >

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_news.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE NEWSLETTER</td>
  </tr>
</table></td>
      </tr>

 <?php
 if ($_GET['send'])
 {
 ?>

      <tr><td>
      Sending
      </td></tr>
<?php
}
?>

      <tr>
        <td><table width="100%" border="0">
          <tr>
            <td>
 <?php

 // Default seite
switch ($_GET['action']) {

	default:

 // Get Customers Groups
 $customer_group_data=$db->Execute("SELECT
                                     customers_status_name,
                                     customers_status_id,
                                     customers_status_image
                                     FROM ".TABLE_CUSTOMERS_STATUS."
                                     WHERE
                                     language_id='".$_SESSION['languages_id']."'");
 $customer_group=array();
 while (!$customer_group_data->EOF) {

      // get single users
     $group_query="SELECT count(*) as count
                                FROM ".TABLE_CUSTOMERS."
                                WHERE customers_newsletter='1' and
                                customers_status='".$customer_group_data->fields['customers_status_id']."'";
     $group_data=$db->Execute($group_query);


 $customer_group[]=array('ID'=>$customer_group_data->fields['customers_status_id'],
                          'NAME'=>$customer_group_data->fields['customers_status_name'],
                          'IMAGE'=>$customer_group_data->fields['customers_status_image'],
                          'USERS'=>$group_data->fields['count']);

$customer_group_data->MoveNext();
 }

 ?>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" width="150" ><?php echo TITLE_CUSTOMERS; ?></td>
          <td class="dataTableHeadingContent"  ><?php echo TITLE_STK; ?></td>
        </tr>

        <?php
for ($i=0,$n=sizeof($customer_group); $i<$n; $i++) {
?>
        <tr>
          <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" valign="middle" align="left"><?php echo twe_image(DIR_WS_ICONS . $customer_group[$i]['IMAGE'], ''); ?><?php echo $customer_group[$i]['NAME']; ?></td>
          <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left"><?php echo $customer_group[$i]['USERS']; ?></td>
        </tr>
        <?php
}
?>
      </table></td>
    <td width="30%" align="right" valign="top""><?php
    echo '<a href="'.twe_href_link(FILENAME_MODULE_NEWSLETTER,'action=new').'">'.twe_image_button('button_new_newsletter.gif').'</a>';


    ?></td>
  </tr>
</table>
 <br>
 <?php

 // get data for newsletter overwiev

 $newsletters_data=$db->Execute("SELECT
                                   newsletter_id,date,title
                                  FROM ".TABLE_MODULE_NEWSLETTER."
                                  WHERE status='0'");
 $news_data=array();
 while (!$newsletters_data->EOF) {

 $news_data[]=array(    'id' => $newsletters_data->fields['newsletter_id'],
                    	'date'=>$newsletters_data->fields['date'],
                        'title'=>$newsletters_data->fields['title']);
$newsletters_data->MoveNext(); 
 }

?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" width="30" ><?php echo TITLE_DATE; ?></td>
          <td class="dataTableHeadingContent" width="80%" ><?php echo TITLE_NOT_SEND; ?></td>
          <td class="dataTableHeadingContent"  >.</td>
        </tr>
<?php
for ($i=0,$n=sizeof($news_data); $i<$n; $i++) {
if ($news_data[$i]['id']!='') {
?>
        <tr>
        <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left"><?php echo $news_data[$i]['date']; ?></td>
          <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" valign="middle" align="left"><?php echo twe_image(DIR_WS_CATALOG.'images/icons/arrow.gif'); ?><a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER,'ID='.$news_data[$i]['id']); ?>"><b><?php echo $news_data[$i]['title']; ?></b></a></td>
          <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left">






          </td>
        </tr>
 <?php

if ($_GET['ID']!='' && $_GET['ID']==$news_data[$i]['id']) {

$total_query="SELECT
                           count(*) as count
                           FROM module_newsletter_temp_".(int)$_GET['ID']."";
$total_data=$db->Execute($total_query);
?>
<tr>
<td class="dataTableContent_products" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left"></td>
<td colspan="2" class="dataTableContent_products" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left"><?php echo TEXT_SEND_TO.$total_data->fields['count']; ?></td>
</tr>
<td class="dataTableContent" valign="top" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left">
  <a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER,'action=delete&ID='.$news_data[$i]['id']); ?>" onClick="return confirm('<?php echo CONFIRM_DELETE; ?>')">
  <?php
  echo twe_image_button('button_delete.gif','Delete','','','style="cursor:hand" onClick="return confirm(\''.DELETE_ENTRY.'\')"').'</a><br>';
  ?>
<a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER,'action=edit&ID='.$news_data[$i]['id']); ?>">
<?php echo twe_image_button('button_edit.gif','Edit','','','style="cursor:hand" onClick="return confirm(\''.DELETE_ENTRY.'\')"').'</a>'; ?>
<a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER,'action=send&ID='.$news_data[$i]['id']); ?>"><br><br><hr noshade>
<?php echo twe_image_button('button_send.gif','Edit','','','style="cursor:hand" onClick="return confirm(\''.DELETE_ENTRY.'\')"').'</a>'; ?>

</td>
<td colspan="2" class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left">
<?php

 // get data
    $newsletters_query="SELECT
                                   title,body,cc,bc
                                  FROM ".TABLE_MODULE_NEWSLETTER."
                                  WHERE newsletter_id='".(int)$_GET['ID']."'";
   $newsletters_data=$db->Execute($newsletters_query);

echo TEXT_TITLE.$newsletters_data->fields['title'].'<br>';

     $customers_status=twe_get_customers_statuses();
     for ($i=0,$n=sizeof($customers_status);$i<$n; $i++) {

     $newsletters_data->fields['bc']=str_replace($customers_status[$i]['id'],$customers_status[$i]['text'],$newsletters_data->fields['bc']);

     }

echo TEXT_TO.$newsletters_data->fields['bc'].'<br>';
//echo TEXT_CC.$newsletters_data['cc'].'<br><br>'.TEXT_PREVIEW;
echo '<table style="border-color: #cccccc; border: 1px solid;" width="100%"><tr><td>'.$newsletters_data->fields['body'].'</td></tr></table>';
?>
</td></tr>
<?php
}
?>

<?php
}
}


?>
</table>
<br><br>
<?php
 $newsletters_data=$db->Execute("SELECT
                                   newsletter_id,date,title
                                  FROM ".TABLE_MODULE_NEWSLETTER."
                                  WHERE status='1'");
 $news_data=array();
 while (!$newsletters_data->EOF) {

 $news_data[]=array(    'id' => $newsletters_data->fields['newsletter_id'],
                        'date'=>$newsletters_data->fields['date'],
                        'title'=>$newsletters_data->fields['title']);
$newsletters_data->MoveNext(); 
 }

?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" width="80%" ><?php echo TITLE_SEND; ?></td>
          <td class="dataTableHeadingContent"><?php echo TITLE_ACTION; ?></td>
        </tr>
<?php
for ($i=0,$n=sizeof($news_data); $i<$n; $i++) {
if ($news_data[$i]['id']!='') {
?>
        <tr>
          <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" valign="middle" align="left"><?php echo $news_data[$i]['date'].'    '; ?><b><?php echo $news_data[$i]['title']; ?></b></td>
          <td class="dataTableContent" style="border-bottom: 1px solid; border-color: #f1f1f1;" align="left">

  <a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER,'action=delete&ID='.$news_data[$i]['id']); ?>" onClick="return confirm('<?php echo CONFIRM_DELETE; ?>')">
  <?php
  echo twe_image(DIR_WS_ICONS.'delete.gif','Delete','','','style="cursor:hand" onClick="return confirm(\''.DELETE_ENTRY.'\')"').'  '.TEXT_DELETE.'</a>&nbsp;&nbsp;';
  ?>
<a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER,'action=edit&ID='.$news_data[$i]['id']); ?>">
<?php echo twe_image(DIR_WS_ICONS.'icon_edit.gif','Edit','','').'  '.TEXT_EDIT.'</a>'; ?>





          </td>
        </tr>
<?php
}
}


?>
</table>

<?php


  break;       // end default page

  case 'edit':

   $newsletters_query="SELECT
                                   title,body,cc
                                  FROM ".TABLE_MODULE_NEWSLETTER."
                                  WHERE newsletter_id='".(int)$_GET['ID']."'";
   $newsletters_data=$db->Execute($newsletters_query);

  case 'safe':
  case 'new':  // action for NEW newsletter!

$customers_status=twe_get_customers_statuses();


  echo twe_draw_form('edit_newsletter',FILENAME_MODULE_NEWSLETTER,'action=save','post','enctype="multipart/form-data"').twe_draw_hidden_field('ID',$_GET['ID']);
  ?>

  <br><br>
 <table class="main" width="100%" border="0">
   </tr>
      <tr>
      <td width="10%"><?php echo TEXT_TITLE; ?></td>
      <td width="90%"><?php echo twe_draw_textarea_field('title', 'soft', '100%', '3',$newsletters_data->fields['title']); ?></td>
   </tr>
            <tr>
      <td width="10%"><?php echo TEXT_TO; ?></td>
      <td width="90%"><?php
for ($i=0,$n=sizeof($customers_status);$i<$n; $i++) {

     $group_query="SELECT count(*) as count
                                FROM ".TABLE_CUSTOMERS."
                                WHERE customers_newsletter='1' and
                                customers_status='".$customers_status[$i]['id']."'";
     $group_data=$db->Execute($group_query);

     $group_query="SELECT count(*) as count
                                FROM ".TABLE_CUSTOMERS."
                                WHERE
                                customers_status='".$customers_status[$i]['id']."'";
     $group_data_all=$db->Execute($group_query);

echo twe_draw_checkbox_field('status['.$i.']', 'yes',true).' '.$customers_status[$i]['text'].'  <i>(<b>'.$group_data->fields['count'].'</b>'.TEXT_USERS.$group_data_all->fields['count'].TEXT_CUSTOMERS.'<br>';

}
echo twe_draw_checkbox_field('status_all', 'yes',false).' <b>'.TEXT_NEWSLETTER_ONLY.'</b>';

       ?></td>
         </tr>
      <tr>
      <td width="10%" valign="top"><?php echo TEXT_BODY; ?></td>
      <td width="90%"><?php echo twe_draw_textarea_field('newsletter_body', 'soft', '120', '45', stripslashes($newsletters_data->fields['body'])); ?></td>
   </tr>
   </table>
   <a href="<?php echo twe_href_link(FILENAME_MODULE_NEWSLETTER); ?>"><?php echo twe_image_button('button_back.gif', IMAGE_BACK); ?></a>
   <right><?php echo twe_image_submit('button_save.gif', IMAGE_SAVE); ?></right>
  </form>
  <?php

  break;
} // end switch

?>


</td>

          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php if (USE_SPAW=='true' && $_GET['action']!='') { ?>

<script type="text/javascript">
      HTMLArea.loadPlugin("SpellChecker");
      HTMLArea.loadPlugin("TableOperations");
      HTMLArea.loadPlugin("FullPage");
      HTMLArea.loadPlugin("CharacterMap");
      HTMLArea.loadPlugin("ContextMenu");
      HTMLArea.loadPlugin("ImageManager");
	  
HTMLArea.onload = function() {
var editor= new HTMLArea("newsletter_body");
editor.registerPlugin(TableOperations);
editor.registerPlugin(FullPage);
editor.registerPlugin(ContextMenu);
editor.registerPlugin(CharacterMap);
editor.registerPlugin(ImageManager);
editor.generate();
};
HTMLArea.init();
</script>
<?php } ?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>