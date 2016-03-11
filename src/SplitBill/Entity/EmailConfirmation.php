<?php

namespace SplitBill\Entity;

class EmailConfirmation {

    /**
     * @var int|null The user ID. Uniquely identifies a user.
     */
    private $userId;
    /**
     * @var string The token.
     */
    private $token;

    /**
     * EmailConfirmation constructor.
     * @param int|null $userId
     * @param string $token
     */
    public function __construct($userId, $token) {
        $this->userId = $userId;
        $this->token = $token;
    }

    /**
     * @return int|null
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

}
