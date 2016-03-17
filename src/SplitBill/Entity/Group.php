<?php

namespace SplitBill\Entity;

use DateTime;

class Group {

    private $groupId;
    private $name;
    private $isOpen;
    private $isSecret;

    /**
     * @var DateTime The timestamp for creation.
     */
    private $createdAt;
    /**
     * @var DateTime When the record was updated.
     */
    private $updatedAt;
    /**
     * @var bool
     */
    private $deleted;

    /**
     * Group constructor.
     * @param $name
     * @param $isOpen
     * @param $isSecret
     * @param $deleted
     */
    public function __construct($name, $isOpen, $isSecret, $deleted = false) {
        $this->name = $name;
        $this->isOpen = $isOpen;
        $this->isSecret = $isSecret;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
        $this->touch();
    }

    /**
     * @return boolean
     */
    public function isDeleted() {
        return $this->deleted;
    }

    /**
     * @param boolean $deleted
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;
        $this->touch();
    }

    /**
     * @return mixed
     */
    public function getIsOpen() {
        return $this->isOpen;
    }

    /**
     * @param mixed $isOpen
     */
    public function setIsOpen($isOpen) {
        $this->isOpen = $isOpen;
        $this->touch();
    }

    /**
     * @return mixed
     */
    public function getIsSecret() {
        return $this->isSecret;
    }

    /**
     * @param mixed $isSecret
     */
    public function setIsSecret($isSecret) {
        $this->isSecret = $isSecret;
        $this->touch();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @deprecated
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @deprecated
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId($groupId) {
        $this->groupId = $groupId;
        $this->touch();
    }

    /**
     * Update the updatedAt date.
     */
    public function touch() {
        $this->updatedAt = new DateTime();
    }
}
