<?php
// Define the project version
  define('PROJECT_VERSION', 'Twe-Commerce v3.0-utf8');

  
  // Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');

  // define the filenames used in the project
  define('FILENAME_ACCOUNTING', 'accounting.php');
  define('FILENAME_BACKUP', 'backup.php');
  define('FILENAME_BANNER_MANAGER', 'banner_manager.php');
  define('FILENAME_BANNER_STATISTICS', 'banner_statistics.php');
  define('FILENAME_CACHE', 'cache.php');
  define('FILENAME_CATALOG_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_COUNTRIES', 'countries.php');
  define('FILENAME_CURRENCIES', 'currencies.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAME_CUSTOMERS_STATUS', 'customers_status.php');
  define('FILENAME_DEFAULT', 'start.php');
  define('FILENAME_DEFINE_LANGUAGE', 'define_language.php');
  define('FILENAME_GEO_ZONES', 'geo_zones.php');
  define('FILENAME_LANGUAGES', 'languages.php');
  define('FILENAME_MAIL', 'mail.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_MODULES', 'modules.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_ORDERS_INVOICE', 'invoice.php');
  define('FILENAME_ORDERS_PACKINGSLIP', 'packingslip.php');
  define('FILENAME_ORDERS_STATUS', 'orders_status.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SERVER_INFO', 'server_info.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_TAX_CLASSES', 'tax_classes.php');
  define('FILENAME_TAX_RATES', 'tax_rates.php');
  define('FILENAME_WHOS_ONLINE', 'whos_online.php');
  define('FILENAME_ZONES', 'zones.php');
  define('FILENAME_TIME', 'time.php');
  define('FILENAME_START', 'start.php');
  define('FILENAME_STATS_STOCK_WARNING', 'stats_stock_warning.php');
  define('FILENAME_TPL_BOXES','templates_boxes.php');
  define('FILENAME_TPL_MODULES','templates_modules.php');
  define('FILENAME_NEW_ATTRIBUTES','new_attributes.php');
  define('FILENAME_XSELL_PRODUCTS', 'xsell_products.php');
  define('FILENAME_LOGOUT','../logoff.php');
  define('FILENAME_LOGIN','../login.php');
  define('FILENAME_CREATE_ACCOUNT','create_account.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS','create_account_success.php');
  define('FILENAME_CUSTOMER_MEMO','customer_memo.php');
  define('FILENAME_CONTENT_MANAGER','content_manager.php');
  define('FILENAME_CONTENT_PREVIEW','content_preview.php');
  define('FILENAME_SECURITY_CHECK','security_check.php');
  define('FILENAME_PRINT_ORDER','print_order.php');
  define('FILENAME_CREDITS','credits.php');
  define('FILENAME_PRINT_PACKINGSLIP','print_packingslip.php');
  define('FILENAME_MODULE_NEWSLETTER','module_newsletter.php');
  define('FILENAME_GV_ADMIN', 'gv_queue.php');
  define('FILENAME_GV_QUEUE', 'gv_queue.php');
  define('FILENAME_GV_MAIL', 'gv_mail.php');
  define('FILENAME_GV_SENT', 'gv_sent.php');
  define('FILENAME_COUPON_ADMIN', 'coupon_admin.php');
  define('FILENAME_POPUP_MEMO', 'popup_memo.php');
  define('FILENAME_SHIPPING_STATUS', 'shipping_status.php');
  define('FILENAME_SALES_REPORT','stats_sales_report.php');
  define('FILENAME_MODULE_EXPORT','module_export.php');
  define('FILENAME_EASY_POPULATE','easypopulate.php');
  define('FILENAME_BLACKLIST', 'blacklist.php');
  define('FILENAME_NEWS_CATEGORIES', 'news_categories.php');
  define('FILENAME_FILE_MANAGER', 'file_manager.php');
  define('FILENAME_LAYOUT_CONTROLLER','layout_controller.php');
  define('FILENAME_BACKUP_MYSITE', 'backup_mysite.php');
  // define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_ADMIN_ACCESS', 'admin_access');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_TPL_MODULES_CONFIGURATION', 'tpl_modules_configuration ');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_CUSTOMERS_IP', 'customers_ip');
  define('TABLE_CUSTOMERS_STATUS', 'customers_status');
  define('TABLE_CUSTOMERS_STATUS_HISTORY', 'customers_status_history');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_NEWSLETTERS', 'newsletters');
  define('TABLE_NEWSLETTERS_HISTORY', 'newsletters_history');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');
  define('TABLE_TIME', 'time');
  define('TABLE_BOX_ALIGN','box_align');
  define('TABLE_PRODUCTS_XSELL', 'products_xsell');
  define('TABLE_CUSTOMERS_MEMO','customers_memo');
  define('TABLE_CONTENT_MANAGER','content_manager');
  define('TABLE_PRODUCTS_CONTENT','products_content');
  define('TABLE_MEDIA_CONTENT','media_content');
  define('TABLE_MODULE_NEWSLETTER','module_newsletter');
  define('TABLE_CM_FILE_FLAGS', 'cm_file_flags');
  define('TABLE_COUPON_GV_QUEUE', 'coupon_gv_queue');
  define('TABLE_COUPON_GV_CUSTOMER', 'coupon_gv_customer');
  define('TABLE_COUPON_EMAIL_TRACK', 'coupon_email_track');
  define('TABLE_COUPON_REDEEM_TRACK', 'coupon_redeem_track');
  define('TABLE_COUPONS', 'coupons');
  define('TABLE_COUPONS_DESCRIPTION', 'coupons_description');
  define('TABLE_SHIPPING_STATUS', 'shipping_status');
  define('TABLE_BLACKLIST', 'card_blacklist'); 
  define('TABLE_PHPBB_USERS', 'customers');
  define('TABLE_PHPBB_SESSIONS', 'phpbb_sessions');
  define('TABLE_PHPBB_POSTS', 'phpbb_posts');
  define('TABLE_PHPBB_TOPICS', 'phpbb_topics');
  define('TABLE_PHPBB_VOTE_USERS', 'phpbb_vote_voters');
  define('TABLE_PHPBB_GROUPS', 'phpbb_groups');
  define('TABLE_PHPBB_USER_GROUP', 'phpbb_user_group');
  define('TABLE_PHPBB_AUTH_ACCESS', 'phpbb_auth_access');
  define('TABLE_PHPBB_TOPICS_WATCH', 'phpbb_topics_watch');
  define('TABLE_PHPBB_BANLIST', 'phpbb_banlist');  
  define('TABLE_PHPBB_PRIVMSGS', 'phpbb_privmsgs');  
  define('TABLE_PHPBB_PRIVMSGS_TEXT', 'phpbb_privmsgs_text');  
//START MASTER PRODUCTS
  define('TABLE_PRODUCTS_TO_MASTER','products_to_master');
  //END MASTER PRODUCTS
  define('TABLE_DB_CACHE', 'db_cache');
  define('TABLE_NEWS_CATEGORIES', 'news_categories');
  define('TABLE_NEWS_CATEGORIES_DESCRIPTION', 'news_categories_description');
  define('TABLE_NEWS_PRODUCTS', 'news_products');
  define('TABLE_NEWS_PRODUCTS_DESCRIPTION', 'news_products_description');
  define('TABLE_NEWS_PRODUCTS_TO_CATEGORIES', 'news_products_to_categories');
  define('TABLE_PRODUCTS_MORE_IMAGES', 'products_more_images');
  define('TABLE_LAYOUT_BOXES', 'layout_boxes');
?>