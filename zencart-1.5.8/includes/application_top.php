<?php
/**
 * application_top.php Common actions carried out at the start of each page invocation.
 *
 * Initializes common classes & methods. Controlled by an array which describes
 * the elements to be initialised and the order in which that happens.
 * see  {@link  https://docs.zen-cart.com/dev/code/init_system/} for more details.
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Zcwilt 2022 Aug 09 Modified in v1.5.8-alpha2 $
 */

use App\Models\PluginControl;
use App\Models\PluginControlVersion;
use Zencart\FileSystem\FileSystem;
use Zencart\PluginManager\PluginManager;
use Zencart\InitSystem\InitSystem;

// Set session ID
$zenSessionId = 'zenid';

/**
 * inoculate against hack attempts which waste CPU cycles
 */
$contaminated = (isset($_FILES['GLOBALS']) || isset($_REQUEST['GLOBALS'])) ? true : false;
$paramsToAvoid = array('GLOBALS', '_COOKIE', '_ENV', '_FILES', '_GET', '_POST', '_REQUEST', '_SERVER', '_SESSION', 'HTTP_COOKIE_VARS', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_RAW_POST_DATA', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS');
$paramsToAvoid[] = 'autoLoadConfig';
$paramsToAvoid[] = 'mosConfig_absolute_path';
$paramsToAvoid[] = 'function';
$paramsToAvoid[] = 'hash';
$paramsToAvoid[] = 'main';
$paramsToAvoid[] = 'vars';
foreach($paramsToAvoid as $key) {
  if (isset($_GET[$key]) || isset($_POST[$key]) || isset($_COOKIE[$key])) {
    $contaminated = true;
    break;
  }
}
$paramsToCheck = array($zenSessionId, 'main_page', 'cPath', 'products_id', 'language', 'currency', 'action', 'manufacturers_id', 'pID', 'pid', 'reviews_id', 'filter_id', 'sort', 'number_of_uploads', 'notify', 'page_holder', 'chapter', 'alpha_filter_id', 'typefilter', 'disp_order', 'id', 'key', 'music_genre_id', 'record_company_id', 'set_session_login', 'faq_item', 'edit', 'delete', 'search_in_description', 'dfrom', 'pfrom', 'dto', 'pto', 'inc_subcat', 'payment_error', 'order', 'gv_no', 'pos', 'addr', 'error', 'count', 'error_message', 'info_message', 'cID', 'page', 'credit_class_error_code');
if (!$contaminated) {
  foreach($paramsToCheck as $key) {
    if (isset($_GET[$key]) && !is_array($_GET[$key])) {
      if (substr($_GET[$key], 0, 4) == 'http' || strstr($_GET[$key], '//')) {
        $contaminated = true;
        break;
      }
      $len = (in_array($key, array($zenSessionId, 'error_message', 'payment_error'))) ? 255 : 43;
      if (isset($_GET[$key]) && strlen($_GET[$key]) > $len) {
        $contaminated = true;
        break;
      }
    }
  }
}
unset($paramsToCheck, $paramsToAvoid, $key);
if ($contaminated)
{
  header('HTTP/1.1 406 Not Acceptable');
  exit(0);
}
unset($contaminated, $len);
/* *** END OF INOCULATION *** */

// if session id is reconfigured, then we want to exclude its use immediately
if ($zenSessionId !== 'zenid') {
    unset($_GET['zenid'], $_GET['amp;zenid'], $_REQUEST['zenid']);
}

/**
 * boolean used to see if we are in the admin script, obviously set to false here.
 */
define('IS_ADMIN_FLAG', false);
/**
 * integer saves the time at which the script started.
 */
define('PAGE_PARSE_START_TIME', microtime());
@ini_set("arg_separator.output","&");
@ini_set("html_errors","0");
/**
 * Ensure minimum PHP version.
 * This is intended to run before any dependencies are required
 * See https://www.zen-cart.com/requirements or run zc_install to see actual requirements!
 */
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 70205) {
    require 'includes/templates/template_default/templates/tpl_zc_phpupgrade_default.php';
    exit(0);
}
/**
 * Set the local configuration parameters - mainly for developers
 */
if (file_exists('includes/local/configure.php')) {
  /**
   * load any local(user created) configure file.
   */
  include('includes/local/configure.php');
}
/**
 * boolean if true the autoloader scripts will be parsed and their output shown. For debugging purposes only.
 */
define('DEBUG_AUTOLOAD', false);
/**
 * set the level of error reporting
 *
 * Note STRICT_ERROR_REPORTING should never be set to true on a production site.
 * It is mainly there to show php warnings during testing/bug fixing phases.
 */
if (DEBUG_AUTOLOAD || (defined('STRICT_ERROR_REPORTING') && STRICT_ERROR_REPORTING == true)) {
  @ini_set('display_errors', TRUE);
  error_reporting(defined('STRICT_ERROR_REPORTING_LEVEL') ? STRICT_ERROR_REPORTING_LEVEL : E_ALL);
} else {
    error_reporting(0);
}

@date_default_timezone_set(date_default_timezone_get());
/**
 * check for and include load application parameters
 */
if (file_exists('includes/configure.php')) {
  /**
   * load the main configure file.
   */
  include('includes/configure.php');
} else if (!defined('DIR_FS_CATALOG') && !defined('HTTP_SERVER') && !defined('DIR_WS_CATALOG') && !defined('DIR_WS_INCLUDES')) {
  $problemString = 'includes/configure.php not found';
  require('includes/templates/template_default/templates/tpl_zc_install_suggested_default.php');
  exit;
}
/**
 * if main configure file doesn't contain valid info (ie: is dummy or doesn't match filestructure, display assistance page to suggest running the installer)
 */
if (!defined('DIR_FS_CATALOG') || !is_dir(DIR_FS_CATALOG.'/includes/classes')) {
  $problemString = 'includes/configure.php file contents invalid.  ie: DIR_FS_CATALOG not valid or not set';
  require('includes/templates/template_default/templates/tpl_zc_install_suggested_default.php');
  exit;
}
/**
 * check for and load system defined path constants
 */
if (file_exists('includes/defined_paths.php')) {
    /**
     * load the system-defined path constants
     */
    require('includes/defined_paths.php');
} else {
    die('ERROR: /includes/defined_paths.php file not found. Cannot continue.');
    exit;
}
require DIR_FS_CATALOG . DIR_WS_FUNCTIONS . 'php_polyfills.php';
require DIR_FS_CATALOG . DIR_WS_FUNCTIONS . 'zen_define_default.php';
/**
 * include the list of extra configure files
 */
if ($za_dir = @dir(DIR_WS_INCLUDES . 'extra_configures')) {
  while ($zv_file = $za_dir->read()) {
    if (preg_match('~^[^\._].*\.php$~i', $zv_file) > 0) {
      /**
       * load any user/contribution specific configuration files.
       */
      include(DIR_WS_INCLUDES . 'extra_configures/' . $zv_file);
    }
  }
  $za_dir->close();
  unset($za_dir);
}
$autoLoadConfig = [];
if (isset($loaderPrefix)) {
 $loaderPrefix = preg_replace('/[^a-z_]/', '', $loaderPrefix);
} else {
  $loaderPrefix = 'config';
}
$loader_file = $loaderPrefix . '.core.php';
require 'includes/initsystem.php';
/**
 * determine install status
 */
if (( (!file_exists('includes/configure.php') && !file_exists('includes/local/configure.php')) ) || (DB_TYPE == '') || (!file_exists('includes/classes/db/' .DB_TYPE . '/query_factory.php')) || !file_exists('includes/autoload_func.php')) {
  $problemString = 'includes/configure.php file empty or file not found, OR wrong DB_TYPE set, OR cannot find includes/autoload_func.php which suggests paths are wrong or files were not uploaded correctly';
  require('includes/templates/template_default/templates/tpl_zc_install_suggested_default.php');
  header('location: zc_install/index.php');
  exit;
}
/**
 * psr-4 autoloading
 */
require DIR_FS_CATALOG . DIR_WS_CLASSES . 'vendors/AuraAutoload/src/Loader.php';
require DIR_FS_CATALOG . 'laravel/vendor/autoload.php';
$psr4Autoloader = new \Aura\Autoload\Loader;
$psr4Autoloader->register();
require('includes/psr4Autoload.php');
require DIR_FS_CATALOG . DIR_WS_CLASSES . 'class.base.php';
require DIR_FS_CATALOG . DIR_WS_CLASSES . 'query_cache.php';

$queryCache = new QueryCache();
require DIR_FS_CATALOG . DIR_WS_CLASSES . 'cache.php';
$zc_cache = new cache();

require 'includes/init_includes/init_file_db_names.php';
require 'includes/init_includes/init_database.php';

require DIR_FS_CATALOG . 'includes/application_laravel.php';

$pluginManager = new PluginManager(new PluginControl(), new \App\Models\PluginControlVersion());
$installedPlugins = $pluginManager->getInstalledPlugins();
$pluginManager = new PluginManager(new PluginControl, new App\Models\PluginControlVersion);

$fs = new FileSystem;
$fs->loadFilesFromPluginsDirectory($installedPlugins, 'catalog/includes/extra_configures', '~^[^\._].*\.php$~i');
$fs->loadFilesFromPluginsDirectory($installedPlugins, 'catalog/includes/extra_datafiles', '~^[^\._].*\.php$~i');

foreach ($installedPlugins as $plugin) {
    $namespaceAdmin = 'Zencart\\Plugins\\Admin\\' . ucfirst($plugin['unique_key']);
    $namespaceCatalog = 'Zencart\\Plugins\\Catalog\\' . ucfirst($plugin['unique_key']);
    $filePath = DIR_FS_CATALOG . 'zc_plugins/' . $plugin['unique_key'] . '/' . $plugin['version'] . '/';
    $filePathAdmin = $filePath . 'classes/admin';
    $filePathCatalog = $filePath . 'classes/';
    $psr4Autoloader->addPrefix($namespaceAdmin, $filePathAdmin);
    $psr4Autoloader->addPrefix($namespaceCatalog, $filePathCatalog);
}


$autoLoadConfig = array();
if (isset($loaderPrefix)) {
    $loaderPrefix = preg_replace('/[^a-z_]/', '', $loaderPrefix);
} else {
    $loaderPrefix = 'config';
}
$loader_file = $loaderPrefix . '.core.php';
$initSystem = new InitSystem('catalog', $loaderPrefix, new FileSystem, $pluginManager, $installedPlugins);

if (defined('DEBUG_AUTOLOAD') && DEBUG_AUTOLOAD == true) $initSystem->setDebug(true);

$loaderList = $initSystem->loadAutoLoaders();

$initSystemList = $initSystem->processLoaderList($loaderList);

require DIR_FS_CATALOG . 'includes/autoload_func.php';
/**
 * load the counter code
**/
if (empty($spider_flag)) {
// counter and counter history
  require(DIR_WS_INCLUDES . 'counter.php');
}
// get customers unique IP that paypal does not touch
$customers_ip_address = $_SERVER['REMOTE_ADDR'];
if (!isset($_SESSION['customers_ip_address'])) {
  $_SESSION['customers_ip_address'] = $customers_ip_address;
}
