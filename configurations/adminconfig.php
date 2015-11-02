<?php

/**
 * Filename: config.php
 * use this file to include on all php file
 * 
 * @author  Arnold Orbista
 */

/**
 * @var string Absolute path of the application root with trailing slash. eg. /home/htdocs/ or c:/htdocs/
 */
define('SYSCONFIG_ROOT_PATH', str_replace("\\","/",realpath(dirname(__FILE__).'/../').'/'));
define('SYSCONFIG_ROOT_PATH2', str_replace("\\","/",realpath(dirname(__FILE__).'/../../').'/'));

/**
 * @var string Base URL for IPay Application and OrangeHRM
 */
$pathInfo = pathinfo($_SERVER['PHP_SELF']);
$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
define('BASE_URL',$protocol."localhost".$pathInfo['dirname']."/");
define('SYSCONFIG_ORANGE_URL','http://localhost/orangehrm/');
define('SYSCONFIG_DTR_URL','http://localhost/dtr/');

/**
 *  @var string Application ID and Application Token
 */
define('SYSCONFIG_APP_ID',1);
define('SYSCONFIG_APP_TOKEN','1234');
/**
 * @var string database used for OrangeHRM System
 */
define('SYSCONFIG_ORANGEHRM_DB','hris_epi');

/**
 * @var string Absolute path of the 'classes' directory with trailing slash.
 */
define('SYSCONFIG_CLASS_PATH', SYSCONFIG_ROOT_PATH.'classes/');


/**
 * @var string path to php files called by ajah.
 added by: mark
 */
define('EXTERNAL_PHP_FILES',SYSCONFIG_ROOT_PATH.'external/');

/**
 * @var string Absolute path of 3rdparty libraries. includes trailing slash
 */
define('SYSCONFIG_3RDPARTY_PATH', SYSCONFIG_ROOT_PATH.'configuration/');

/**
 * @var string Absolute path of the 'resources' directory with trailing slash.
 */
define('SYSCONFIG_RESOURCE_PATH', SYSCONFIG_ROOT_PATH.'resource/');

/**
 * @var string Absolute path of the 'modules' directory with trailing slash.
 */
define('SYSCONFIG_MODULE_PATH', SYSCONFIG_ROOT_PATH.'modules/');

/**
 * @var string Absolute path of the 'dbbackup' directory with trailing slash.
 */
define('SYSCONFIG_DBBACKUP_PATH', SYSCONFIG_ROOT_PATH.'dbmodel/backup/');


/**
 * @var string Absolute path of the smarty libraries. includes trailing slash
 */
define('SMARTY_DIR', SYSCONFIG_3RDPARTY_PATH.'smarty/');

/**
 * @var string hostname of application, w/o proto scheme. eg. dswd.gov.ph
 */
define('SYSCONFIG_URL_HOST', $_SERVER['SERVER_ADDR']);

/**
 * @var string dir path of the application url with trailing slash. eg. /otsfs/
 */
define('SYSCONFIG_URL_PATH',str_replace('\\','/',dirname($_SERVER['PHP_SELF'])).((substr(dirname($_SERVER['PHP_SELF']),strlen(dirname($_SERVER['PHP_SELF']))-1,1)!='\\')?"/":""). "../");

/**
 * @var string Absolute URL path of the images directory with trailing slash.
 */
define('SYSCONFIG_IMAGE_PATH', SYSCONFIG_URL_PATH.'images/');

/**
 * @var string Absolute path of the 'modules' directory with trailing slash.
 */
define('SYSCONFIG_THEME_PATH', SYSCONFIG_ROOT_PATH.'themes/');
define('SYSCONFIG_THEME_URLPATH', SYSCONFIG_URL_PATH.'themes/');
define('SYSCONFIG_DEFAULT_IMAGES', SYSCONFIG_URL_PATH.'themes/default/images/');
define('SYSCONFIG_DEFAULT_SCRIPT', SYSCONFIG_URL_PATH.'includes/jscript/tabbar/');

/**
 * @path to redirect to images located in included folder.
 *
 */
define('SYSCONFIG_DEFAULT_IMAGES_INCTEMP', SYSCONFIG_URL_PATH.'includes/jscript/ThemeOffice/');
define('SYSCONFIG_DEFAULT_IMAGES_INCTEMP2', SYSCONFIG_ROOT_PATH2.SYSCONFIG_DEFAULT_IMAGES_INCTEMP.'includes/jscript/ThemeOffice/');

//@notes added by rhea a. bonifacio 07.30.2007 for dynamic folder
define('SYSCONFIG_URL_PATH_DYNAMIC',str_replace('\\','/',dirname($_SERVER['PHP_SELF'])).((substr(dirname($_SERVER['PHP_SELF']),strlen(dirname($_SERVER['PHP_SELF']))-1,1)!='\\')?"/":""). "../../");
define('SYSCONFIG_IMAGES_ROOT_PATH',SYSCONFIG_URL_PATH_DYNAMIC. 'themes/default/images/');

/**
 * @var bool if the application will use SSL for secured pages
 */
define('SYSCONFIG_USE_SSL', true);

/**
 * @var string server's http port
 */
define('SYSCONFIG_PORT_HTTP', 80);

/**
 * @var string server's https (ssl) port
 */
define('SYSCONFIG_PORT_HTTPS', 443);

define('APPCONFIG_DEFAULT_THEME','default');

/**
 * @var int Timeout for idle application session (in seconds)
 */
/*define('APPCONFIG_IDLE_TIMEOUT', 60*60*3); // 3 hours
define('SYSCONFIG_MAX_IDLETIME', 60*30); // default of 30 minutes
*/
define('APPCONFIG_IDLE_TIMEOUT', 60*60*1); // 1 hour
define('SYSCONFIG_MAX_IDLETIME', 60*60*1); // default of 1 hr

/**
 * @var string Default date format
 */
define('APPCONFIG_FORMAT_DATE', 'Y-m-d');
define('APPCONFIG_FORMAT_TIME', 'g:i A'); // g:i A T

/**
 * @var string Default date-time format
 */
define('APPCONFIG_FORMAT_DATETIME', APPCONFIG_FORMAT_DATE.' '.APPCONFIG_FORMAT_TIME);
define('APPCONFIG_FORMAT_DATETIME_24H', APPCONFIG_FORMAT_DATE.' H:i:s');

/**
 * @var string default date time format for sql parameter input
 */
define('APPCONFIG_FORMAT_DATETIME_SQL', '%Y-%m-%d %H:%i:%s');

define('COUNTRY_ID_PHILIPPINES',168); // required for some conditionals

/////////////////////////////////////////////////////////////////////////

include("common.config.php");

// locale settings
//date_default_timezone_set('Asia/Manila'); 
//setlocale(LC_ALL,'Asia/Manila'); 


//$ADODB_CACHE_DIR = SYSCONFIG_ROOT_PATH.SYSCONFIG_THEME_PATH.SYSCONFIG_THEME.'/compile';
ini_set('magic_quotes_gpc', false);
ini_set('max_execution_time', 9999999999);
ini_set('memory_limit', '1064M');
ini_set('max_input_time', 9999999999);

//set the timezone for philippines
date_default_timezone_set('Asia/Manila');
?>