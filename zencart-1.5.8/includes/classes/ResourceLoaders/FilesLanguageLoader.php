<?php
/**
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: brittainmark 2022 Aug 23 Modified in v1.5.8-alpha2 $
 */

namespace Zencart\LanguageLoader;

use Zencart\FileSystem\FileSystem;

class FilesLanguageLoader extends BaseLanguageLoader
{
    protected $mainLoader;
    
    public function loadExtraLanguageFiles($rootPath, $language, $fileName, $extraPath = '')
    {
        if ($this->mainLoader->hasLanguageFile($rootPath, $language, $fileName, $extraPath .  '/' . $this->templateDir)) {
            $this->loadFileDefineFile($rootPath . $language . $extraPath . '/' . $this->templateDir . '/' . $fileName);
        } else {
            $this->loadFileDefineFile($rootPath . $language . $extraPath . '/' . $fileName);
        }
    }

    public function loadFileDefineFile($defineFile)
    {
        $pathInfo = pathinfo(($defineFile));
        if (preg_match('~^lang\.~i', $pathInfo['basename'])) {
            return false;
        }
        if (!is_file($defineFile)) {
            return false;
        }
        if ($this->mainLoader->isFileAlreadyLoaded($defineFile)) {
            return false;
        }
        $this->mainLoader->addLanguageFilesLoaded('legacy', $defineFile);
        include_once($defineFile);
        return true;
    }
}
