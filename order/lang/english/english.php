<?php
/* -----------------------------------------------------------------------------------------
   $Id: english.php,v 1.6 2004/04/01 14:19:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(german.php,v 1.119 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (german.php,v 1.25 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en'
@setlocale(LC_TIME, 'en_US.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

// page title
define('TITLE', STORE_NAME);

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function twe_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}
// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="en"');

define('HEADER_TITLE_TOP', 'Top');
define('HEADER_TITLE_CATALOG', 'Catalog');

 // text for gender
define('MALE', 'Mr.');
define('FEMALE', 'Mrs.');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Mrs.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// text for quick purchase
define('IMAGE_BUTTON_ADD_QUICK', 'Quick Purchase!');
define('BOX_ADD_PRODUCT_ID_TEXT', 'Quick purchase enter the product-ID.');

// text for gift voucher redeeming
define('IMAGE_REDEEM_GIFT','Redeem Gift Voucher!');

define('BOX_TITLE_STATISTICS','Statistic:');
define('BOX_ENTRY_CUSTOMERS','Customers');
define('BOX_ENTRY_PRODUCTS','Products');
define('BOX_ENTRY_REVIEWS','Reviews');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_SEARCH_TEXT', 'Use keywords to find a special product.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Advanced Search');

// reviews box text in includes/boxes/reviews.php
define('BOX_REVIEWS_WRITE_REVIEW', 'Review this product!');
define('BOX_REVIEWS_NO_REVIEWS', 'There are not any reviews yet');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s of 5 stars!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_SHOPPING_CART_EMPTY', '0 Products');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_NOTIFICATIONS_NOTIFY', 'Send me news about this product <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Stop sending me news about this product <b>%s</b>');

// manufacturer box text
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'More Products');

define('BOX_HEADING_ADD_PRODUCT_ID','Add to cart!');
define('BOX_HEADING_SEARCH','Search!');

define('BOX_INFORMATION_CONTACT', 'Contact');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Recommend To A Friend');
define('BOX_TELL_A_FRIEND_TEXT', 'Recommend this product simply by eMail.');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Choose please');
define('TYPE_BELOW', 'Fill in below please');

// javascript messages
define('JS_ERROR', 'Missing necessary information!\nPlease fill in correctly.\n\n');

define('JS_REVIEW_TEXT', '* The text must consist at least of ' . REVIEW_TEXT_MIN_LENGTH . ' alphabetic characters..\n');
define('JS_REVIEW_RATING', '* Enter your review.\n');
define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please choose a method of payment for your order.\n');
define('JS_ERROR_SUBMITTED', 'This page has already been confirmed. Please click okay and wait until the process has finished.');
define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please choose a method of payment for your order.');
define('CATEGORY_COMPANY', 'Company data');
define('CATEGORY_PERSONAL', 'Your personal data');
define('CATEGORY_ADDRESS', 'Your address');
define('CATEGORY_CONTACT', 'Your contact information');
define('CATEGORY_OPTIONS', 'Options');
define('CATEGORY_PASSWORD', 'Your password');

define('ENTRY_COMPANY', 'Company name:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Mr. / Mrs. / Title:');
define('ENTRY_GENDER_ERROR', 'Please select your gender.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'First name:');
define('ENTRY_FIRST_NAME_ERROR', 'Your first name must consist of at least  ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Last name:');
define('ENTRY_LAST_NAME_ERROR', 'Your last name must consist of at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME_ERROR_EXISTS', '&nbsp;<span class="errorText">The Last name you entered already existst in our datebase - please login with your existing account or create a new account with a new Last name .</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your date of birth has to be entered in the following form DD.MM.YYYY (e.g. 21.05.1970) ');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* ');
define('ENTRY_EMAIL_ADDRESS', 'eMail-address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your eMail-address must consist of at least  ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'The eMail-address your entered is incorrect - please check it');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'The eMail-address you entered already existst in our datebase - please login with your existing account or create a new account with a new eMail-address .');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Street/Nr.:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street/Nr must consist of at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'District:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'ZIP Code:');
define('ENTRY_POST_CODE_ERROR', 'Your ZIP Code must consist of at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', 'Your city must consist of at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'State:');
define('ENTRY_STATE_ERROR', 'Your state must consist of at least ' . ENTRY_STATE_MIN_LENGTH . ' scharacters.');
define('ENTRY_STATE_ERROR_SELECT', 'Please select your state out of the list.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', 'Please select your country out of the list.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone number must consist of at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Telefax number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'subscribed to');
define('ENTRY_NEWSLETTER_NO', 'not subscribed to');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_ERROR', 'Your password must consist of at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Your passwords do not match.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirmation:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Current password:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your password must consist of at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW', 'New password:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Your new password must consist of at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Your passwords do not match.');
define('PASSWORD_HIDDEN', '--HIDDEN--');


// constants for use in twe_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seiten:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Show <b>%d</b> to <b>%d</b> (of in total <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Show <b>%d</b> to <b>%d</b> (of in total <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Show <b>%d</b> to <b>%d</b> (of in total <b>%d</b> reviews)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Show <b>%d</b> to <b>%d</b> (of in total <b>%d</b> new products)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Show <b>%d</b> to <b>%d</b> (of in total <b>%d</b> special offers)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'first page');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'previous page');
define('PREVNEXT_TITLE_NEXT_PAGE', 'next page');
define('PREVNEXT_TITLE_LAST_PAGE', 'last page');
define('PREVNEXT_TITLE_PAGE_NO', 'page %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous %d pages');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next %d pages');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;previous]');
define('PREVNEXT_BUTTON_NEXT', '[next&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'New address');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address book');
define('IMAGE_BUTTON_BACK', 'Back');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change address');
define('IMAGE_BUTTON_CHECKOUT', 'Checkout');
define('IMAGE_BUTTON_CLEAR', 'Clear Cart');
define('IMAGE_BUTTON_DELETE', 'Remove');
define('IMAGE_BUTTON_INCREASE', 'Plus');
define('IMAGE_BUTTON_DECREASE', 'Minus');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm order');
define('IMAGE_BUTTON_CONTINUE', 'Next');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue purchase');
define('IMAGE_BUTTON_DELETE', 'Delete');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Change dates');
define('IMAGE_BUTTON_HISTORY', 'Order history');
define('IMAGE_BUTTON_LOGIN', 'Login');
define('IMAGE_BUTTON_IN_CART', 'Into the cart');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');
define('IMAGE_BUTTON_QUICK_FIND', 'Express search');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Delete Notifications');
define('IMAGE_BUTTON_REVIEWS', 'Reviews');
define('IMAGE_BUTTON_SEARCH', 'Search');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping options');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Recommend');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_UPDATE_CART', 'Update shopping cart');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Evaluation');

define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');
define('SMALL_IMAGE_BUTTON_VIEW', 'View');

define('ICON_ARROW_RIGHT', 'Show more');
define('ICON_CART', 'Into the cart');
define('ICON_SUCCESS', 'Success');
define('ICON_WARNING', 'Warning');

define('TEXT_GREETING_PERSONAL', 'Nice to encounte you again <span class="greetUser">%s!</span> Would you like to visit our <a href="%s"><u>new products</u></a> ?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s , please  <a href="%s"><u>login</u></a>  with your account.</small>');
define('TEXT_GREETING_GUEST', 'Cordially welcome  <span class="greetUser">visitor!</span> Would you like to <a href="%s"><u>login</u></a>? Or would you like to create an <a href="%s"><u>account</u></a> ?');

define('TEXT_SORT_PRODUCTS', 'Sorting of the items is ');
define('TEXT_DESCENDINGLY', 'descending');
define('TEXT_ASCENDINGLY', 'ascending');
define('TEXT_BY', ' after ');

define('TEXT_REVIEW_BY', 'from %s');
define('TEXT_REVIEW_WORD_COUNT', '%s words');
define('TEXT_REVIEW_RATING', 'Review: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Date added: %s');
define('TEXT_NO_REVIEWS', 'There aren愒 any reviews yet.');

define('TEXT_NO_NEW_PRODUCTS', 'There are no new products at the moment.');

define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');

define('TEXT_REQUIRED', '<span class="errorText">required</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>Error:</small> Your mail can愒 be send by your SMTP server. Please control the attitudes in the php.ini file and make necessary changes!</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: The installation directory is still available onto: ' . DIR_FS_DOCUMENT_ROOT . 'twe_installer. Please delete this directory in case of security!');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: TWE-Commerce is able to write into the configuration directory: ' .DIR_FS_DOCUMENT_ROOT . 'includes/configure.php. That represents a possible safety hazard - please correct the user access rights for this directory!');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: Directory for sesssions doesnt exist: ' . twe_session_save_path() . '. Sessions will not work until this directory has been created!');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: TWE-Commerce is not abe to write into the session directory: ' . twe_session_save_path() . '. DSessions will not work until the user access rights for this directory have benn changed!');
define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is activated (enabled) - Please deactivate (disabled) this PHP feature in php.ini and restart your webserver!');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: Directory for articel download doesnt exist: ' . DIR_FS_DOWNLOAD . '. This feature will not work until this directory has been created!');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The "valid to" date ist invalid.<br>Please correct your information.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The "Credit card number ", you entered, is invalid.<br>Please correct your information.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first 4 signs of your Credit Card are: %s<br>If this information is correct, your type of card is not accepted.<br>Please correct your information.');

/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  Please leave this comment intact together with the
  following copyright announcement.
  
  Copyright announcement changed due to the permissions
  from LG Hamburg from 28th February 2003 / AZ 308 O 70/03
*/
define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oldpa.com.tw" target="_blank">Pro-eCommerce</a><br>Powered by <a href="http://www.oldpa.com.tw" target="_blank">Pro-eCommerce</a>');

//  conditions check

define('ERROR_CONDITIONS_NOT_ACCEPTED', 'If you dont accept our General Business Conditions, we are not able to accept your order!');

define('SUB_TITLE_OT_DISCOUNT','Discount:');
define('SUB_TITLE_SUB_NEW','Total:');

define('NOT_ALLOWED_TO_SEE_PRICES','You dont have the permission to see the prices ');
define('NOT_ALLOWED_TO_ADD_TO_CART','You dont have the permission to put items into the shopping cart');

define('BOX_LOGINBOX_HEADING', 'Welcome back!');
define('BOX_LOGINBOX_EMAIL', 'eMail-address:');
define('BOX_LOGINBOX_PASSWORD', 'Password:');
define('IMAGE_BUTTON_LOGIN', 'Login');
define('BOX_ACCOUNTINFORMATION_HEADING','Information');

define('BOX_LOGINBOX_STATUS','Customer group:');
define('BOX_LOGINBOX_INCL','All prices incl. Sales tax');
define('BOX_LOGINBOX_EXCL','All prices excl. Sales tax');
define('TAX_ADD_TAX','incl. ');
define('TAX_NO_TAX','plus ');
define('BOX_LOGINBOX_DISCOUNT','Product discount');
define('BOX_LOGINBOX_DISCOUNT_TEXT','Discount');
define('BOX_LOGINBOX_DISCOUNT_OT','');
define('TAX_ALL_TAX','Tax :');

define('NOT_ALLOWED_TO_SEE_PRICES_TEXT','You dont have the permission to see the prices, please create an account.');

define('TEXT_DOWNLOAD','Download');
define('TEXT_VIEW','View');

define('TEXT_BUY', '1 x \'');
define('TEXT_NOW', '\' order');
define('TEXT_GUEST','Visitor');
define('TEXT_NO_PURCHASES', 'You have not yet made an order.');


// Warnings
define('SUCCESS_ACCOUNT_UPDATED', 'Your account has been updated successfully.');
define('SUCCESS_NEWSLETTER_UPDATED', 'Your newsletter subscription has been updated successfully!');
define('SUCCESS_NOTIFICATIONS_UPDATED', 'Your product notifications have been updated successfully!');
define('SUCCESS_PASSWORD_UPDATED', 'Your password has been changed successfully!');
define('ERROR_CURRENT_PASSWORD_NOT_MATCHING', 'The entered password does not match within the stored password. Please try again.');
define('TEXT_MAXIMUM_ENTRIES', '<font color="#ff0000"><b>Reference:</b></font> You are able to choose out of %s entries in you address book!');
define('SUCCESS_ADDRESS_BOOK_ENTRY_DELETED', 'The selected entry has been extinguished successfully.');
define('SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED', 'Your address book has been updated sucessfully!');
define('WARNING_PRIMARY_ADDRESS_DELETION', 'The standard postal address can not be deleted. Please select another standard postal address first. Than the entry can be deleted.');
define('ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY', 'This address book entry is not available.');
define('ERROR_ADDRESS_BOOK_FULL', 'Your address book can not include any further postal addresses. Please delete an address which is not longer used. Than a new entry can be made.');

//Advanced Search
define('ENTRY_CATEGORIES', 'Categories:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Include subcategories');
define('ENTRY_MANUFACTURERS', 'Manufacturer:');
define('ENTRY_PRICE_FROM', 'Price over:');
define('ENTRY_PRICE_TO', 'Price up to:');
define('TEXT_ALL_CATEGORIES', 'All categories');
define('TEXT_ALL_MANUFACTURERS', 'All manufacturers');
define('JS_AT_LEAST_ONE_INPUT', '* One of the following fields must be filled:\n    Keywords\n    Date added from\n    Date added to\n    Price over\n    Price up to\n');
define('JS_INVALID_FROM_DATE', '* Invalid from date\n');
define('JS_INVALID_TO_DATE', '* Invalid up to Date\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* The from date must be larger or same size as up to now\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* Price over, must be a number\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* Price up to, must be a number\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* Price up to must be larger or same size as Price over.\n');
define('JS_INVALID_KEYWORDS', '* Invalid search key\n');
define('TEXT_NO_PRODUCTS', 'No items which correspond to the search criteria were found.');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>WARNING:</b></font></small> If you already have an account, please login <a href="%s"><u><b>here</b></u></a>.');
define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> The entered \'eMail-address\' and/or the \'password\' do not match.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>WARNING:</b></font> Your inputs as visitor will be automatically linked to your account. <a href="javascript:session_win();">[More Information]</a>');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>WARNING:</b></font> The entered eMail-address is not registered. Please try again.');
define('TEXT_PASSWORD_SENT', 'A new password was sent by eMail.');
define('TEXT_PRODUCT_NOT_FOUND', 'Product not found!');
define('TEXT_MORE_INFORMATION', 'For further informations, please visit the <a href="%s" target="_blank"><u>homepage</u></a> to this product.');
define('TEXT_DATE_ADDED', 'This Product was added to our catalog on %s.');
define('TEXT_DATE_AVAILABLE', '<font color="#ff0000">This Product is expected to be on stock again on %s wieder vorr&auml;tig sein.</font>');
define('TEXT_CART_EMPTY', 'Your cart is empty.');
define('SUB_TITLE_SUB_TOTAL', 'Subtotal:');
define('SUB_TITLE_TOTAL', 'Total:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'The products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' , are not on stock with your entered amount.<br>Please reduce your purchase order quantity for the marked products. Thank you');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'The products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' , are not on stock with your entered amount.<br>The entered amount will be supplied in a short period of time by us. On request, we would also be able to make a part delivery.');

define('HEADING_TITLE_TELL_A_FRIEND', 'Recommend \'%s\'');
define('HEADING_TITLE_ERROR_TELL_A_FRIEND', 'Recommend product');
define('ERROR_INVALID_PRODUCT', 'The product chosen by you was not found!');


define('NAVBAR_TITLE_ACCOUNT', 'Your account');
define('NAVBAR_TITLE_1_ACCOUNT_EDIT', 'Your account');
define('NAVBAR_TITLE_2_ACCOUNT_EDIT', 'Changing your personal data');
define('NAVBAR_TITLE_1_ACCOUNT_HISTORY', 'Your account');
define('NAVBAR_TITLE_2_ACCOUNT_HISTORY', 'Your done orders');
define('NAVBAR_TITLE_1_ACCOUNT_HISTORY_INFO', 'Your account');
define('NAVBAR_TITLE_2_ACCOUNT_HISTORY_INFO', 'Done orders');
define('NAVBAR_TITLE_3_ACCOUNT_HISTORY_INFO', 'Order number %s');
define('NAVBAR_TITLE_1_ACCOUNT_NEWSLETTERS', 'Your account');
define('NAVBAR_TITLE_2_ACCOUNT_NEWSLETTERS', 'Newsletter subscription');
define('NAVBAR_TITLE_1_ACCOUNT_NOTIFICATIONS', 'Your account');
define('NAVBAR_TITLE_2_ACCOUNT_NOTIFICATIONS', 'Product information');
define('NAVBAR_TITLE_1_ACCOUNT_PASSWORD', 'Your account');
define('NAVBAR_TITLE_2_ACCOUNT_PASSWORD', 'Change password');
define('NAVBAR_TITLE_1_ADDRESS_BOOK', 'Your account');
define('NAVBAR_TITLE_2_ADDRESS_BOOK', 'Address book');
define('NAVBAR_TITLE_1_ADDRESS_BOOK_PROCESS', 'Your account');
define('NAVBAR_TITLE_2_ADDRESS_BOOK_PROCESS', 'Address book');
define('NAVBAR_TITLE_ADD_ENTRY_ADDRESS_BOOK_PROCESS', 'New entry');
define('NAVBAR_TITLE_MODIFY_ENTRY_ADDRESS_BOOK_PROCESS', 'Change entry');
define('NAVBAR_TITLE_DELETE_ENTRY_ADDRESS_BOOK_PROCESS', 'Delete Entry');
define('NAVBAR_TITLE_ADVANCED_SEARCH', 'Advanced Search');
define('NAVBAR_TITLE1_ADVANCED_SEARCH', 'Advanced Search');
define('NAVBAR_TITLE2_ADVANCED_SEARCH', 'Search results');
define('NAVBAR_TITLE_1_CHECKOUT_CONFIRMATION', 'Checkout');
define('NAVBAR_TITLE_2_CHECKOUT_CONFIRMATION', 'Confirmation');
define('NAVBAR_TITLE_1_CHECKOUT_PAYMENT', 'Checkout');
define('NAVBAR_TITLE_2_CHECKOUT_PAYMENT', 'Method of payment');
define('NAVBAR_TITLE_1_PAYMENT_ADDRESS', 'Checkout');
define('NAVBAR_TITLE_2_PAYMENT_ADDRESS', 'Change billing address');
define('NAVBAR_TITLE_1_CHECKOUT_SHIPPING', 'Checkout');
define('NAVBAR_TITLE_2_CHECKOUT_SHIPPING', 'Shipping information');
define('NAVBAR_TITLE_1_CHECKOUT_SHIPPING_ADDRESS', 'Checkout');
define('NAVBAR_TITLE_2_CHECKOUT_SHIPPING_ADDRESS', 'Change shipping address');
define('NAVBAR_TITLE_1_CHECKOUT_SUCCESS', 'Checkout');
define('NAVBAR_TITLE_2_CHECKOUT_SUCCESS', 'Success');
define('NAVBAR_TITLE_CONTACT_US', 'Contact');
define('NAVBAR_TITLE_CREATE_ACCOUNT', 'Create account');
define('NAVBAR_TITLE_1_CREATE_ACCOUNT_SUCCESS', 'Create account');
define('NAVBAR_TITLE_2_CREATE_ACCOUNT_SUCCESS', 'Success');
if ($navigation->snapshot['page'] == FILENAME_CHECKOUT_SHIPPING) {
  define('NAVBAR_TITLE_LOGIN', 'Order');
} else {
  define('NAVBAR_TITLE_LOGIN', 'Login');
}
define('NAVBAR_TITLE_LOGOFF','Good bye');
define('NAVBAR_TITLE_1_PASSWORD_FORGOTTEN', 'Login');
define('NAVBAR_TITLE_2_PASSWORD_FORGOTTEN', 'Password forgotten');
define('NAVBAR_TITLE_PRODUCTS_NEW', 'New products');
define('NAVBAR_TITLE_SHOPPING_CART', 'Shopping cart');
define('NAVBAR_TITLE_SPECIALS', 'Special offers');
define('NAVBAR_TITLE_COOKIE_USAGE', 'Cookie Usage');
define('NAVBAR_TITLE_PRODUCT_REVIEWS', 'Reviews');
define('NAVBAR_TITLE_TELL_A_FRIEND', 'Recommend product');
define('NAVBAR_TITLE_REVIEWS_WRITE', 'Opinions');
define('NAVBAR_TITLE_REVIEWS','Reviews');
define('NAVBAR_TITLE_SSL_CHECK', 'Note on safety');
define('NAVBAR_TITLE_CREATE_GUEST_ACCOUNT','Create account');

define('TEXT_EMAIL_SUCCESSFUL_SENT','Your eMail was sent successfully!');
define('ERROR_MAIL','Please check the entered data in the form');
define('CATEGORIE_NOT_FOUND','Category was not found');
define('NAVBAR_TITLE_BBFORUM','Forum');

define('BOX_INFORMATION_GV', 'Gift Voucher FAQ');
define('BOX_HEADING_GIFT_VOUCHER', 'Gift Voucher Account'); 
define('GV_FAQ', 'Gift Voucher FAQ');
define('ERROR_REDEEMED_AMOUNT', 'Congratulations, you have redeemed ');
define('ERROR_NO_REDEEM_CODE', 'You did not enter a redeem code.');  
define('ERROR_NO_INVALID_REDEEM_GV', 'Invalid Gift Voucher Code'); 
define('ERROR_INVALID_REDEEM_COUPON', 'Redeem Success!');
define('TABLE_HEADING_CREDIT', 'Credits Available');
define('ENTRY_AMOUNT_CHECK_ERROR', 'You do not have enough funds to send this amount.'); 

define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
                                        ' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
define('EMAIL_SUBJECT', 'Message from ' . STORE_NAME);
define('EMAIL_SEPARATOR', '----------------------------------------------------------------------------------------');

define('EMAIL_GV_TEXT_HEADER', 'Congratulations, You have received a gift voucher worth %s');
define('EMAIL_GV_TEXT_SUBJECT', 'A gift from %s');
define('EMAIL_GV_FROM', 'This Gift Voucher has been sent to you by %s');
define('EMAIL_GV_MESSAGE', 'With a message saying ');
define('EMAIL_GV_SEND_TO', 'Hi, %s');
define('EMAIL_GV_REDEEMED', 'To redeem this Gift Voucher, please click on the link below. Please also write down the redemption code which is %s. In case you have problems.');
define('EMAIL_GV_LINK', 'To redeem please click ');
define('EMAIL_GV_VISIT', ' or visit ');
define('EMAIL_GV_ENTER', ' and enter the code ');
define('EMAIL_GV_FIXED_FOOTER', 'If you are have problems redeeming the Gift Voucher using the automated link above, ' . "\n" .
                                'you can also enter the Gift Voucher code during the checkout process at our store.' . "\n\n");
define('EMAIL_GV_SHOP_FOOTER', '');
define('MAIN_MESSAGE', 'You have decided to send a gift voucher worth %s to %s who\'s email address is %s<br><br>The text accompanying the email will read<br><br>Dear %s<br><br>
                        You have been sent a Gift Voucher worth %s by %s');

define('PERSONAL_MESSAGE', '%s says:');
define('NAVBAR_GV_FAQ', 'Gift Voucher FAQ');
define('NAVBAR_GV_REDEEM', 'Redeem Voucher');
define('NAVBAR_GV_SEND', 'Send Voucher');
define('HEADER_NEWS','<font size="2">News</font>');
define('READ_MORE_NEWS','More....');
define('TEXT_NEWS_DATE_ADDED', '<font size="1">Add Date Time %s.</font>');
define('TEXT_NEWS_VIEWED', '<font size="1">View&nbsp;<strong>(<strong> %s <strong>)<strong></font>');
define('TEXT_DISPLAY_NUMBER_OF_NEWS', 'show <b>%d</b> to <b>%d</b> (of in total<b>%d</b>News)');
define('TEXT_QUOTES_TITLE','Your quotes contains :');
define('TEXT_SHOPPING_CART_TITLE','Your shopping cart contains :');
define('JS_STATE_SELECT', '-- Select about --');
define('PLEASE_SELECT', 'Please Select');
define('ENTRY_PLEASE','Entry Keyword');
define('REDEEMED_COUPON','Redeemed successfully!');

//myEdit
define('HINT_PRODUCT_INFO','Please select one by one for different product needs!');
define('IMAGE_BUTTON_ADD_CART', 'add cart');
define('IMAGE_BUTTON_HOME', 'go home page');

?>