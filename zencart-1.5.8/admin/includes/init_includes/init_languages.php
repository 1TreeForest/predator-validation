<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Dec 24 Modified in v1.5.8-alpha $
 */

use Zencart\LanguageLoader\LanguageLoaderFactory;

if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
// set the language
  if (!isset($_SESSION['language']) || isset($_GET['language'])) {

    include(DIR_FS_CATALOG . DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($_GET['language']) && !empty($_GET['language'])) {
      $lng->set_language($_GET['language']);
      $zco_notifier->notify('NOTIFY_LANGUAGE_CHANGE_REQUESTED_BY_ADMIN_VISITOR', $_GET['language'], $lng);
    } else {
      $lng->get_browser_language();
      $lng->set_language(DEFAULT_LANGUAGE);
    }

    if (!is_file(DIR_WS_LANGUAGES . 'lang.' . $lng->language['directory'] . '.php')) {
      $lng->set_language('en');
    }

    $_SESSION['language'] = (!empty($lng->language['directory']) ? $lng->language['directory'] : 'english');
    $_SESSION['languages_id'] = (!empty($lng->language['id']) ? (int)$lng->language['id'] : 1);
    $_SESSION['languages_code'] = (!empty($lng->language['code']) ? $lng->language['code'] : 'en');
  }

// temporary patch for lang override chicken/egg quirk
  $template_query = $db->Execute("SELECT template_dir
                                  FROM " . TABLE_TEMPLATE_SELECT . "
                                  WHERE template_language in (" . (int)$_SESSION['languages_id'] . ', 0' . ")
                                  ORDER BY template_language DESC");
  $template_dir = $template_query->fields['template_dir'];

// include the language translations
$current_page = ($PHP_SELF == 'home.php') ? 'index.php' : $PHP_SELF;
$languageLoaderFactory = new LanguageLoaderFactory();
$languageLoader = $languageLoaderFactory->make('admin', $installedPlugins, $current_page, $template_dir);
$languageLoader->loadInitialLanguageDefines();
$languageLoader->finalizeLanguageDefines();
