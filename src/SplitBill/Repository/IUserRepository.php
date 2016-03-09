<?php

namespace SplitBill\Repository;

interface IUserRepository {

    public function getByEmail($email);
    public function getByItsUsername($itsUsername);

}
