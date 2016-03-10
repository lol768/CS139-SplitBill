<?php

namespace SplitBill\Repository;

use SplitBill\Entity\User;

interface IUserRepository {
    /** @return User */
    public function getByEmail($email);
    /** @return User */
    public function getByItsUsername($itsUsername);
    /** @return User */
    public function getById($userId);
}
