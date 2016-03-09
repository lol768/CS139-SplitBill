<?php

namespace SplitBill\ItsAuthentication;

interface IOAuthManager {

    public function getOAuthStartUrl();

    /**
     * @return ItsUserInfo
     */
    public function getUserInfoUsingUuid($uuid);
}
