<?php

namespace BillSplitter\ItsAuthentication;

interface IOAuthManager {

    public function getOAuthStartUrl();

    /**
     * @return ItsUserInfo
     */
    public function getUserInfoUsingUuid($uuid);
}
