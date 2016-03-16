<?php

namespace SplitBill\Repository;

use SplitBill\Entity\EmailConfirmation;

interface IEmailConfirmationRepository {
    /**
     * @param $userId
     * @return EmailConfirmation
     */
    public function getByUserId($userId);

    /**
     * @param $token
     * @return EmailConfirmation
     */
    public function getByToken($token);

    /**
     * @param EmailConfirmation $confirmation
     * @return EmailConfirmation
     */
    public function add(EmailConfirmation $confirmation);
}
