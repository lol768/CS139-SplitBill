<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\Bill;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqliteBillRepository extends AbstractSqliteRepository implements IBillRepository {

    protected function mapFromArray(array $results) {
        // TODO: Implement mapFromArray() method.
    }

    /**
     * @param Bill $bill
     * @return Bill
     */
    public function add(Bill $bill) {
        $sql = "INSERT INTO bills VALUES(NULL, :amount, :description, :user_id, :group_id, :company, :created_at, :updated_at);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $user->getName(), SQLITE3_TEXT);
        $stmt->bindValue(":password", $user->getPassword(), SQLITE3_TEXT);
        $stmt->bindValue(":email", $user->getEmail(), SQLITE3_TEXT);
        $stmt->bindValue(":created_at", $user->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $user->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":its_username", $user->getItsUsername(), SQLITE3_TEXT);
        $stmt->bindValue(":active", $user->getActive() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->bindValue(":has_avatar", $user->getHasAvatar() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->execute();
        $user->setUserId($this->db->lastInsertRowID());
        return $user;
    }

    /**
     * @param Bill $bill
     * @return Bill
     */
    public function update(Bill $bill) {
        // TODO: Implement update() method.
    }
}
