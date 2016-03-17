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

    /** @return User[] */
    public function getFuzzyMatches($search);

    /**
     * @param User $user
     * @return User
     */
    public function add(User $user);

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user);
}
