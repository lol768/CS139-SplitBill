<?php

namespace SplitBill\Session;

interface IFlashSession extends IUserSession {

    public function cleanupOldFlashItems();
    public function extendLife($key);

}
