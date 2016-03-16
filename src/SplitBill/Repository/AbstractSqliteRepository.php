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

    /**
     * @param SQLite3Stmt $stmt
     * @return object
     */
    protected function getMultipleEntitiesViaStatement($stmt) {
        $out = $stmt->execute();
        $finalObjects = array();
        while (($results = $out->fetchArray(SQLITE3_ASSOC)) !== false) {
            $finalObjects[] = $this->mapFromArray($results);
        }
        return $finalObjects;
    }

    protected abstract function mapFromArray(array $results);
}
