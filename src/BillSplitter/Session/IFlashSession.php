<?php

namespace BillSplitter\Session;

interface IFlashSession extends IUserSession {

    public function cleanupOldFlashItems();

}
