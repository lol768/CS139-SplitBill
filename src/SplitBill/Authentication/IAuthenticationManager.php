<?php

namespace SplitBill\Authentication;

use SplitBill\Entity\User;

interface IAuthenticationManager {

    /**
     * @return User
     */
    public function getEffectiveUser();
    /**
     * @return User
     */
    public function getRealUser();

    public function setActualUserId($userId);
    public function masquerade($userId);
    public function unmasquerade();

    public function logout();
}
