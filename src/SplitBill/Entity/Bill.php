<?php

namespace SplitBill\Entity;

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
    }

    /**
     * @return mixed
     */
    public function getBillId() {
        return $this->billId;
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
}
