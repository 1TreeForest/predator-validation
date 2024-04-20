<?php
/**
 * ajaxLoadMainSql.php
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Scott C Wilson 2021 Jul 13 Modified in v1.5.8-alpha $
 */
define('IS_ADMIN_FLAG', false);
define('DIR_FS_INSTALL', __DIR__ . '/');
define('DIR_FS_ROOT', realpath(__DIR__ . '/../') . '/');

require(DIR_FS_INSTALL . 'includes/application_top.php');

$error = FALSE;

$db_type = 'mysql';


require_once(DIR_FS_INSTALL . 'includes/classes/class.zcDatabaseInstaller.php');
$options = array('db_host'=>$_POST['db_host'], 'db_user'=>$_POST['db_user'], 'db_password'=>$_POST['db_password'], 'db_name'=>$_POST['db_name'], 'db_charset'=>$_POST['db_charset'], 'db_prefix'=>$_POST['db_prefix'], 'db_type'=>$db_type);
// trim spaces from inputs
foreach($options as $key => $val) {
  $options[$key] = trim($val);
}
$dbInstaller = new zcDatabaseInstaller($options);
$result = $dbInstaller->getConnection();
$extendedOptions = array('doJsonProgressLogging'=>TRUE, 'doJsonProgressLoggingFileName'=>DEBUG_LOG_FOLDER . '/progress.json', 'id'=>'main', 'message'=>TEXT_CREATING_DATABASE);
$file = DIR_FS_INSTALL . 'sql/install/mysql_zencart.sql';
logDetails('processing file ' . $file);
$error = $dbInstaller->parseSqlFile($file, $extendedOptions);
if ($error)
{
  echo json_encode(array('error'=>$error, 'file'=>$file)); die();
}
// localization file
$charset = $_POST['db_charset'];
if (!in_array($charset, array('utf8', 'latin1'))) $charset = 'utf8';
$file = DIR_FS_INSTALL . 'sql/install/mysql_' . $charset . '.sql';
if (file_exists($file))
{
  $extendedOptions = array('doJsonProgressLogging'=>TRUE, 'doJsonProgressLoggingFileName'=>DEBUG_LOG_FOLDER . '/progress.json', 'id'=>'main', 'message'=>TEXT_LOADING_CHARSET_SPECIFIC);
  logDetails('processing file ' . $file);
  $error = $dbInstaller->parseSqlFile($file, $extendedOptions);
}
if ($error)
{
  echo json_encode(array('error'=>$error, 'file'=>$file)); die();
}
// Demo data
if (isset($_POST['demoData']))
{
  $extendedOptions = array('doJsonProgressLogging'=>TRUE, 'doJsonProgressLoggingFileName'=>DEBUG_LOG_FOLDER . '/progress.json', 'id'=>'main', 'message'=>TEXT_LOADING_DEMO_DATA);
  $file = DIR_FS_INSTALL . 'sql/demo/mysql_demo.sql';
  logDetails('processing file ' . $file);
  $error = $dbInstaller->parseSqlFile($file, $extendedOptions);
  // system('unzip --q demo_images/images.zip -d ../images/'); 
  $za = new ZipArchive;
  if ($za->open('demo_images/images.zip') === TRUE) {
    $za->extractTo('../images');
    $za->close();
  }
}
if ($error)
{
  echo json_encode(array('error'=>$error, 'file'=>$file)); die();
}
// Save data
logDetails('saving cfg keys');
$error = $dbInstaller->updateConfigKeys();
if ($error)
{
  echo json_encode(array('error'=>$error, 'file'=>$file)); die();
}

// Plugins
$pluginsfolder = DIR_FS_INSTALL . 'sql/plugins/';
if ($d = dir($pluginsfolder)) {
  while ($entry = $d->read()) {
    if (!is_dir($pluginsfolder . $entry)) {
      if (preg_match('~^[^\._].*\.sql$~', $entry) > 0) {
        $extendedOptions = array('doJsonProgressLogging'=>TRUE, 'doJsonProgressLoggingFileName'=>DEBUG_LOG_FOLDER . '/progress.json', 'id'=>'main', 'message'=>TEXT_LOADING_PLUGIN_DATA . ' ' . $entry);
        $file = $pluginsfolder . $entry;
        logDetails('processing file ' . $file);
        $error = $dbInstaller->parseSqlFile($file, $extendedOptions);
      }
    }
  }
  $d->close();
}

echo json_encode(array('error'=>$error, 'file'=>$file));  die();

