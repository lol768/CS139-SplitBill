<?php

namespace SplitBill\Entity;

use DateTime;

class Bill {

    private $billId;
    private $userId;
    private $groupId;
    private $amount;
    private $description;
    private $company;
    private $createdAt;
    private $updatedAt;

    /**
     * Bill constructor.
     *
     * @param $userId
     * @param $groupId
     * @param $amount
     * @param $description
     * @param $company
     */
    public function __construct($userId, $groupId, $amount, $description, $company) {
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->amount = $amount;
        $this->description = $description;
        $this->company = $company;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getBillId() {
        return $this->billId;
    }

    /**
     * @param int $billId
     */
    public function setBillId($billId) {
        $this->billId = $billId;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @param mixed $groupId
     */
    public function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company) {
        $this->company = $company;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
}
