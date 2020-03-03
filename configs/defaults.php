<?php
/**
 * All web site defaults.
 *
 * TODO: Write documentation :-P
 */


// defifn('CHECK_MODULE_ACCESS',	!DEV_VERSION);
defifn('ENTITY_CLASS_CHECK', false);
// defifn('ENTITY_ALWAYS_RELOAD',	true);


// Routes
define('ROUTE_HOME', 'home');
define('ROUTE_LOGIN', 'login');
define('ROUTE_LOGOUT', 'logout');
define('ROUTE_USER_SETTINGS', 'user_settings');
define('ROUTE_FILE_DOWNLOAD', 'file_download');

define('ROUTE_USER_FILE_LIST', 'user_file_list');

define('ROUTE_ADM_DEMO', 'admin_demo');
define('ROUTE_ADM_HOME', ROUTE_ADM_DEMO);
define('ROUTE_ADM_USERS', 'adm_users');
define('ROUTE_ADM_USER_EDIT', 'adm_user_edit');
define('ROUTE_ADM_MYSETTINGS', 'adm_mysettings');

define('ROUTE_DEV_HOME', 'dev_home');
define('ROUTE_DEV_CONFIG', 'dev_config');
define('ROUTE_DEV_SYSTEM', 'dev_system');
define('ROUTE_DEV_COMPOSER', 'dev_composer');
define('ROUTE_DEV_ENTITIES', 'dev_entities');
define('ROUTE_DEV_LOGS', 'dev_loglist');
define('ROUTE_DEV_LOG_VIEW', 'dev_log_view');
define('ROUTE_DEV_APPTRANSLATE', 'dev_app_translate');

// Route's defaults
define('DEFAULT_ROUTE', ROUTE_HOME);
define('DEFAULT_ROUTE_USER', ROUTE_USER_FILE_LIST);
define('DEFAULT_ROUTE_ADMIN', ROUTE_ADM_DEMO);
defifn('DEFAULTHOST', 'share-it.sowapps.com');
defifn('DEFAULTPATH', '');

