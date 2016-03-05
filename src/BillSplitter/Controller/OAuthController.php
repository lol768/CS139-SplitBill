<?php

namespace BillSplitter\Controller;

use BillSplitter\Helper\IControllerHelper;
use BillSplitter\ItsAuthentication\IOAuthManager;
use BillSplitter\Response\RedirectResponse;
use BillSplitter\Session\IFlashSession;

/**
 * Handles login via ITS account.
 *
 * @package BillSplitter\Controller
 */
class OAuthController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    /**
     * @var IOAuthManager The OAuth manager instance.
     */
    private $oauthManager;

    public function __construct(IControllerHelper $helper, IOAuthManager $oauthManager) {
        $this->h = $helper;
        $this->oauthManager = $oauthManager;
    }

    public function getQueryParametersForAction($action, $method) {
        if ($action == "endlogin") {
            return array(
                "uuid" => array("required" => true),
            );
        }
        return array();
    }

    /**
     * GET /startIts.php
     */
    public function getStartLogin() {
        return new RedirectResponse($this->oauthManager->getOAuthStartUrl());
    }

    /**
     * GET /endIts.php
     */
    public function getEndLogin($uuid) {
        $userData = $this->oauthManager->getUserInfoUsingUuid($uuid);
        return $this->h->getViewResponse("itsDebugView", array("user" => $userData));
    }
}
