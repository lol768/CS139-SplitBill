<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\Bill;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqliteBillRepository extends AbstractSqliteRepository implements IBillRepository {

    protected function mapFromArray(array $results) {
        return $this->mapper->mapBillFromArray($results);
    }

    /**
     * @param Bill $bill
     * @return Bill
     */
    public function add(Bill $bill) {
        $sql = "INSERT INTO bills VALUES(NULL, :amount, :description, :user_id, :group_id, :company, :created_at, :updated_at);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":amount", $bill->getAmount(), SQLITE3_INTEGER);
        $stmt->bindValue(":description", $bill->getDescription(), SQLITE3_TEXT);
        $stmt->bindValue(":company", $bill->getCompany(), SQLITE3_TEXT);
        $stmt->bindValue(":user_id", $bill->getUserId(), SQLITE3_INTEGER);
        $stmt->bindValue(":group_id", $bill->getGroupId(), SQLITE3_INTEGER);

        $stmt->bindValue(":created_at", $bill->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $bill->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->execute();
        $bill->setBillId($this->db->lastInsertRowID());
        return $bill;
    }

    /**
     * @param Bill $bill
     * @return Bill
     */
    public function update(Bill $bill) {
        $sql = "UPDATE bills SET amount = :amount, description = :description, user_id = :user_id,
                group_id = :group_id, company = :company, created_at = :created_at, updated_at = :updated_at
                WHERE bill_id = :bill_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":amount", $bill->getAmount(), SQLITE3_INTEGER);
        $stmt->bindValue(":description", $bill->getDescription(), SQLITE3_TEXT);
        $stmt->bindValue(":company", $bill->getCompany(), SQLITE3_TEXT);
        $stmt->bindValue(":user_id", $bill->getUserId(), SQLITE3_INTEGER);
        $stmt->bindValue(":group_id", $bill->getGroupId(), SQLITE3_INTEGER);
        $stmt->bindValue(":bill_id", $bill->getBillId(), SQLITE3_INTEGER);

        $stmt->bindValue(":created_at", $bill->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $bill->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->execute();
        $bill->setGroupId($this->db->lastInsertRowID());
        return $bill;
    }

    /**
     * @param $groupId
     * @return Bill[]
     */
    public function getByGroupId($groupId) {
        $sql = "SELECT * FROM bills WHERE bills.group_id = :bill_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":bill_id", $groupId, SQLITE3_INTEGER);
        return $this->getMultipleEntitiesViaStatement($stmt);
    }
}
