<?php

namespace SplitBill\Session;

interface IFlashSession extends IUserSession {

    public function cleanupOldFlashItems();

}
