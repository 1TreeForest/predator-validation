<?php
/**
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Zcwilt 2020 Jun 02 New in v1.5.8-alpha $
 */

namespace Zencart\LanguageLoader;

use Zencart\FileSystem\FileSystem;

class AdminFilesLanguageLoader extends FilesLanguageLoader
{
    public function loadInitialLanguageDefines($mainLoader)
    {
        $this->mainLoader = $mainLoader;
        $this->loadLanguageExtraDefinitions();
        $this->loadLanguageForView();
        $this->loadBaseLanguageFile();
    }

    protected function loadLanguageForView()
    {
        $this->loadFileDefineFile(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $this->currentPage);
        foreach ($this->pluginList as $plugin) {
            $pluginDir = DIR_FS_CATALOG . 'zc_plugins/' . $plugin['unique_key'] . '/' . $plugin['version'];
            $langFile = $pluginDir . '/admin/includes/languages/'  . $_SESSION['language'] . '/' . $this->currentPage;
            $this->loadFileDefineFile($langFile);
        }
    }

    protected function loadLanguageExtraDefinitions()
    {
        $dirPath = DIR_WS_LANGUAGES . $_SESSION['language'] . '/extra_definitions';
        $fileList = $this->fileSystem->listFilesFromDirectory($dirPath, '~^(?!lang\.).*\.php$~i');
        foreach ($fileList as $file) {
            $this->loadFileDefineFile($dirPath . '/' . $file);
        }
        foreach ($this->pluginList as $plugin) {
            $pluginDir = DIR_FS_CATALOG . 'zc_plugins/' . $plugin['unique_key'] . '/' . $plugin['version'];
            $dirPath = $pluginDir . '/admin/includes/languages/' . $_SESSION['language'] . '/extra_definitions';
            $fileList = $this->fileSystem->listFilesFromDirectory($dirPath, '~^(?!lang\.).*\.php$~i');
            foreach ($fileList as $file) {
                $this->loadFileDefineFile($dirPath . '/' . $file);
            }
        }
    }

    protected function loadBaseLanguageFile()
    {
        $this->loadFileDefineFile(DIR_WS_LANGUAGES . $_SESSION['language'] . '.php');
        $this->loadFileDefineFile(DIR_WS_LANGUAGES . $_SESSION['language'] . "/" . FILENAME_EMAIL_EXTRAS);
        $this->loadFileDefineFile(
            zen_get_file_directory(
                DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/', FILENAME_OTHER_IMAGES_NAMES));
    }
}
