<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\Payment;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqlitePaymentRepository extends AbstractSqliteRepository implements IPaymentRepository {

    protected function mapFromArray(array $results) {
        // TODO: Implement mapFromArray() method.
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function add(Payment $payment) {
        // TODO: Implement add() method.
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function update(Payment $payment) {
        // TODO: Implement update() method.
    }
}
