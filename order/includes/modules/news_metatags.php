<?php
/* -----------------------------------------------------------------------------------------
   $Id: metatags.php,v 1.8 2004/04/26 10:31:17 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 nextcommerce (metatags.php,v 1.7 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
?>
<meta name="robots" content="<?php echo META_ROBOTS; ?>">
<meta name="language" content="<?php echo $language; ?>">
<meta name="author" content="<?php echo META_AUTHOR; ?>">
<meta name="publisher" content="<?php echo META_PUBLISHER; ?>">
<meta name="company" content="<?php echo META_COMPANY; ?>">
<meta name="page-topic" content="<?php echo META_TOPIC; ?>">
<meta name="reply-to" content="<?php echo META_REPLY_TO; ?>">
<meta name="distribution" content="global">
<meta name="revisit-after" content="<?php echo META_REVISIT_AFTER; ?>">
<?php
if (strstr($PHP_SELF, FILENAME_NEWS_PRODUCT_INFO)) {
$product_meta_query = "select pd.products_name,p.products_model,pd.products_meta_title,pd.products_meta_description , pd.products_meta_keywords,pd.products_meta_title from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['newsid'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
$product_meta = $db->Execute($product_meta_query);
?>
<META NAME="description" CONTENT="<?php echo $product_meta->fields['products_meta_description']; ?>">
<META NAME="keywords" CONTENT="<?php echo $product_meta->fields['products_meta_keywords']; ?>">
<title><?php echo TITLE.' - '.$product_meta->fields['products_meta_title'].' '.$product_meta->fields['products_name'].' '.$product_meta->fields['products_model']; ?></title>
<?php
} else {
if ($_GET['news_cPath']) {
if (strpos($_GET['news_cPath'],'_')=='1') {
$arr=explode('_',$_GET['news_cPath']);
$news_cPath=$arr[1];
}
$categories_meta_query="SELECT categories_meta_keywords,
                                            categories_meta_description,
                                            categories_meta_title,
                                            categories_name
                                            FROM ".TABLE_NEWS_CATEGORIES_DESCRIPTION."
                                            WHERE categories_id='".$news_cPath."' and
                                            language_id='".$_SESSION['languages_id']."'";
$categories_meta = $db->Execute($categories_meta_query);
if ($categories_meta->fields['categories_meta_keywords']=='') {
$categories_meta->fields['categories_meta_keywords']=META_KEYWORDS;
}
if ($categories_meta->fields['categories_meta_description']=='') {
$categories_meta->fields['categories_meta_description']=META_DESCRIPTION;
}
if ($categories_meta->fields['categories_meta_title']=='') {
$categories_meta->fields['categories_meta_title']=$categories_meta->fields['categories_name'];
}
?>
<META NAME="description" CONTENT="<?php echo $categories_meta->fields['categories_meta_description']; ?>">
<META NAME="keywords" CONTENT="<?php echo $categories_meta->fields['categories_meta_keywords']; ?>">
<title><?php echo TITLE.' - '.$categories_meta->fields['categories_meta_title']; ?></title>
<?php
} else {
?>
<META NAME="description" CONTENT="<?php echo META_DESCRIPTION; ?>">
<META NAME="keywords" CONTENT="<?php echo META_KEYWORDS; ?>">
<title><?php echo TITLE; ?></title>
<?php
}
}
?>