<?php

namespace SplitBill\Controller;

use SplitBill\DependencyInjection\IContainer;
use SplitBill\Exception\NotImplementedException;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IUserRepository;
use SplitBill\Response\JsonResponse;
use SplitBill\Session\IFlashSession;

class BillsController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;


    public function __construct(IControllerHelper $helper, IUserRepository $userRepo) {
        $this->h = $helper;
    }


    /**
     * GET /bills.php
     */
    public function getDashboard() {
        throw new NotImplementedException();
    }

}
