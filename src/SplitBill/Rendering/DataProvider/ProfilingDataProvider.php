<?php

namespace SplitBill\Rendering\DataProvider;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Database\WrappedSQLite3;
use SplitBill\IApplication;
use SplitBill\Security\IAntiRequestForgery;

class ProfilingDataProvider implements IViewDataProvider {

    /**
     * @var SqliteDatabaseManager
     */
    private $sqliteDatabaseManager;
    /**
     * @var IApplication
     */
    private $app;

    public function __construct(SqliteDatabaseManager $sqliteDatabaseManager, IApplication $app) {
        $this->sqliteDatabaseManager = $sqliteDatabaseManager;
        $this->app = $app;
    }

    public function modifyView($viewName, &$vars) {
        /** @var WrappedSQLite3 $SQLite3 */
        $SQLite3 = $this->sqliteDatabaseManager->getSqlite();
        $vars["queriesLogged"] = $SQLite3->getLoggedQueries();
        $vars['timeDiff'] = microtime(true)*1000 - (1000*$this->app->getStartTime());
    }
}
