<?php

namespace SplitBill\Authentication;

interface IAuthenticationManager {

    public function getEffectiveUser();
    public function getRealUser();

    public function setActualUserId($userId);
    public function masquerade($userId);
    public function unmasquerade();
}
