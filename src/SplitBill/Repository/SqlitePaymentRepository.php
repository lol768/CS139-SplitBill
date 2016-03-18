<?php

namespace SplitBill\Repository;

use SplitBill\Entity\Payment;
use SQLite3Stmt;

class SqlitePaymentRepository extends AbstractSqliteRepository implements IPaymentRepository {

    protected function mapFromArray(array $results) {
        return $this->mapper->mapPaymentFromArray($results);
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function add(Payment $payment) {
        $sql = "INSERT INTO payments VALUES(NULL, :amount, :bill_id, :user_id, :completed, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":amount", $payment->getAmount(), SQLITE3_INTEGER);
        $stmt->bindValue(":bill_id", $payment->getBillId(), SQLITE3_INTEGER);
        $stmt->bindValue(":user_id", $payment->getUserId(), SQLITE3_INTEGER);
        $stmt->bindValue(":completed", $payment->isCompleted() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->bindValue(":created_at", $payment->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $payment->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->execute();
        $payment->setPaymentId($this->db->lastInsertRowID());
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function update(Payment $payment) {
        $sql = "UPDATE payments SET amount = :amount, bill_id = :bill_id, user_id = :user_id, completed = :completed, created_at = :created_at, updated_at = :updated_at WHERE payment_id = :payment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":amount", $payment->getAmount(), SQLITE3_INTEGER);
        $stmt->bindValue(":payment_id", $payment->getPaymentId(), SQLITE3_INTEGER);
        $stmt->bindValue(":bill_id", $payment->getBillId(), SQLITE3_INTEGER);
        $stmt->bindValue(":user_id", $payment->getUserId(), SQLITE3_INTEGER);
        $stmt->bindValue(":completed", $payment->isCompleted() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->bindValue(":created_at", $payment->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $payment->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->execute();
    }

    public function getPendingPaymentsForUserId($userId) {
        $sql = "SELECT * FROM payments WHERE completed = 0 AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        return $this->getMultipleEntitiesViaStatement($stmt);
    }

    /**
     * @param $userId
     * @return Payment[]
     */
    public function getCompletedPaymentsForUserId($userId) {
        $sql = "SELECT * FROM payments WHERE completed = 1 AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        return $this->getMultipleEntitiesViaStatement($stmt);
    }

    /**
     * @param $billId
     * @return Payment[]
     */
    public function getPaymentsForBill($billId) {
        $sql = "SELECT * FROM payments WHERE bill_id = :bill_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":bill_id", $billId, SQLITE3_INTEGER);
        return $this->getMultipleEntitiesViaStatement($stmt);
    }
}
