<?php

namespace BillSplitter\Database;

use \SQLite3;
use BillSplitter\IApplication;

class SqliteDatabaseManager {

    /**
     * @var SQLite3
     */
    private $sqlite;

    /**
     * SqliteDatabaseManager constructor.
     */
    public function __construct(IApplication $app) {
        $config = $app->getConfig();
        $path = $config['sqlite']['path'];
        $this->sqlite = new SQLite3($path);
    }

    /**
     * @return SQLite3
     */
    public function getSqlite() {
        return $this->sqlite;
    }
}
