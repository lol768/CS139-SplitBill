<?php

namespace BillSplitter\Controller;

use BillSplitter\Helper\IControllerHelper;
use BillSplitter\Response\RedirectResponse;
use BillSplitter\Session\IFlashSession;

class HomepageController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    public function __construct(IControllerHelper $helper) {
        $this->h = $helper;
    }

    public function getQueryParametersForAction($action, $method) {
        return array();
    }

    /**
     * GET /index.php
     */
    public function getShowHome() {
        return $this->h->getViewResponse("homepage", array());
    }

}
