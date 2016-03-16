<?php

namespace SplitBill\Repository;

use SQLite3Stmt;

abstract class AbstractSqliteRepository {
    /**
     * @param SQLite3Stmt $stmt
     * @return object
     */
    protected function getSingleEntityViaStatement($stmt) {
        $results = $stmt->execute();
        $results = $results->fetchArray(SQLITE3_ASSOC);
        if ($results === false) {
            return null;
        } else {
            return $this->mapFromArray($results);
        }
    }

    protected abstract function mapFromArray(array $results);
}
