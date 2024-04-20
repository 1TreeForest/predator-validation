<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: brittainmark 2022 Sep 17 Modified in v1.5.8 $
 */

namespace Zencart\PluginSupport;

class ScriptedInstaller
{

    /**
     * $dbConn is a database object 
     * @var object
     */
    protected $dbConn;
    /**
     * $errorContainer is a PluginErrorContainer object
     * @var object
     */
    protected $errorContainer;

    public function __construct($dbConn, $errorContainer)
    {
        $this->dbConn = $dbConn;
        $this->errorContainer = $errorContainer;
    }

    public function doInstall()
    {
        $installed = $this->executeInstall();
        return $installed;
    }

    public function doUninstall()
    {
        $uninstalled = $this->executeUninstall();
        return $uninstalled;
    }

    public function doUpgrade()
    {
        $upgraded = $this->executeUpgrade();
        return $upgraded;
    }

    protected function executeInstall()
    {
        return true;
    }

    protected function executeUninstall()
    {
        return true;
    }

    protected function executeUpgrade()
    {
        return true;
    }

    protected function executeInstallerSql($sql)
    {
        $this->dbConn->dieOnErrors = false;
        $this->dbConn->Execute($sql);
        if ($this->dbConn->error_number !== 0) {
            $this->errorContainer->addError(0, $this->dbConn->error_text, true, PLUGIN_INSTALL_SQL_FAILURE);
            return false;
        }
        $this->dbConn->dieOnErrors = true;
        return true;
    }
}
