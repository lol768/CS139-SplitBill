<?php

namespace SplitBill\Database;

class WrappedSQLite3 extends \SQLite3 {
    private $logged = array();

    public function prepare($query) {
        $this->logged[] = $query;
        return parent::prepare($query);
    }

    public function getLoggedQueries() {
        return $this->logged;
    }
}
