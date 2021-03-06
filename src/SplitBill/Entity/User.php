<?php

namespace SplitBill\Entity;

use DateTime;

class User {

    /**
     * @var int|null The user ID. Uniquely identifies a user.
     */
    private $userId;
    /**
     * @var string The user's full name.
     */
    private $name;
    /**
     * @var string The user's email address.
     */
    private $email;
    /**
     * @var string The user's bcrypt'd password.
     */
    private $password;
    /**
     * @var DateTime The timestamp for creation.
     */
    private $createdAt;
    /**
     * @var DateTime When the record was updated.
     */
    private $updatedAt;

    /**
     * @var string|null
     */
    private $itsUsername;

    /**
     * @var bool Whether the account is active (confirmed email).
     */
    private $active;

    /**
     * @var bool Whether the user has an avatar or not.
     */
    private $hasAvatar = false;

    /**
     * User constructor.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param bool $active Whether the user account is confirmed.
     */
    public function __construct($name, $email, $password, $active=false) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->active = $active;
    }

    /**
     * @return int|null The user ID. Uniquely identifies a user. (null if unsaved)
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return string The user's full name.
     */
    public function getName() {
        return $this->name;
    }

    public function getFirstName() {
        $split = explode(" ", $this->name);
        return $split[0];
    }

    /**
     * @return string The user's email address.
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string The password (bcrypt).
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return DateTime The time at which the record was created.
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @return DateTime The time at which the record was updated.
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @return null|string
     */
    public function getItsUsername() {
        return $this->itsUsername;
    }


    /**
     * @param int|null $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
        $this->touch();
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
        $this->touch();
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
        $this->touch();
    }

    /**
     * @param null|string $itsUsername
     */
    public function setItsUsername($itsUsername) {
        $this->itsUsername = $itsUsername;
        $this->touch();
    }

    /**
     * @param bool $active
     */
    public function setActive($active) {
        $this->active = $active;
        $this->touch();
    }

    /**
     * Update the updatedAt date.
     */
    public function touch() {
        $this->updatedAt = new DateTime();
    }

    // Only used internally...

    /**
     * @param DateTime $createdAt
     * @deprecated You should not call this directly.
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @param DateTime $updatedAt
     * @deprecated You should not call this directly.
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getActive() {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getHasAvatar() {
        return $this->hasAvatar;
    }

    /**
     * @param mixed $hasAvatar
     */
    public function setHasAvatar($hasAvatar) {
        $this->hasAvatar = $hasAvatar;
    }
}
