<?php

namespace SplitBill\Controller;

use SplitBill\Email\IEmailService;
use SplitBill\Exception\NotLoggedInException;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

class HomepageController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IEmailService
     */
    private $emailService;

    public function __construct(IControllerHelper $helper, IEmailService $emailService) {
        $this->h = $helper;
        $this->emailService = $emailService;
    }

    /**
     * GET /index.php
     */
    public function getShowHome() {
        if (array_key_exists("testMail", $_GET)) {
            $this->emailService->sendEmail("A.Williams.4@warwick.ac.uk", "Test mail 5", "testing", array("foo" => "bar"));
            die("Tested.");
        }
        $this->h->setActiveNavigationItem("Home");
        return $this->h->getViewResponse("homepage", array());
    }

}
