<?php

namespace SplitBill\Repository;

use SplitBill\Entity\Payment;

interface IPaymentRepository {

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function add(Payment $payment);

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function update(Payment $payment);

    /**
     * @param $userId
     * @return Payment[]
     */
    public function getPendingPaymentsForUserId($userId);

    /**
     * @param $userId
     * @return Payment[]
     */
    public function getCompletedPaymentsForUserId($userId);
}
