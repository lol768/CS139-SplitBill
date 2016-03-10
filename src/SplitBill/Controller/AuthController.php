<?php

namespace SplitBill\Controller;

use SplitBill\Helper\IControllerHelper;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

class AuthController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    public function __construct(IControllerHelper $helper) {
        $this->h = $helper;
    }

    /**
     * GET /register.php
     */
    public function getShowRegistrationForm() {
        $this->h->setActiveNavigationItem("Register");
        return $this->h->getViewResponse("registerNoModal", array());
    }

}
