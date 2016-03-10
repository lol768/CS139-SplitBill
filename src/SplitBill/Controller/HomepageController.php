<?php

namespace SplitBill\Controller;

use SplitBill\Helper\IControllerHelper;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

class HomepageController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    public function __construct(IControllerHelper $helper) {
        $this->h = $helper;
    }

    /**
     * GET /index.php
     */
    public function getShowHome() {
        $this->h->setActiveNavigationItem("Home");
        return $this->h->getViewResponse("homepage", array());
    }

}
