<?php

namespace SplitBill\Entity;

use DateTime;

class Payment {

    /** @var int */
    private $billId;
    /** @var int */
    private $paymentId;
    /** @var int */
    private $userId;
    /** @var boolean */
    private $completed;
    /** @var int */
    private $amount;
    /** @var DateTime */
    private $createdAt;
    /** @var DateTime */
    private $updatedAt;

    /**
     * Payment constructor.
     * @param int $paymentId
     * @param int $userId
     * @param bool $completed
     * @param int $amount
     */
    public function __construct($paymentId, $userId, $completed, $amount) {
        $this->paymentId = $paymentId;
        $this->userId = $userId;
        $this->completed = $completed;
        $this->amount = $amount;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getBillId() {
        return $this->billId;
    }

    /**
     * @param int $billId
     */
    public function setBillId($billId) {
        $this->billId = $billId;
        $this->touch();
    }

    /**
     * @return int
     */
    public function getPaymentId() {
        return $this->paymentId;
    }

    /**
     * @param int $paymentId
     */
    public function setPaymentId($paymentId) {
        $this->paymentId = $paymentId;
        $this->touch();
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
        $this->touch();
    }

    /**
     * @return boolean
     */
    public function isCompleted() {
        return $this->completed;
    }

    /**
     * @param boolean $completed
     */
    public function setCompleted($completed) {
        $this->completed = $completed;
        $this->touch();
    }

    /**
     * @return int
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
        $this->touch();
    }


    /**
     * @param mixed $createdAt
     * @deprecated
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @param mixed $updatedAt
     * @deprecated
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Update the updatedAt date.
     */
    public function touch() {
        $this->updatedAt = new DateTime();
    }
}
