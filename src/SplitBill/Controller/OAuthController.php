<?php

namespace SplitBill\Controller;

use SplitBill\Helper\IControllerHelper;
use SplitBill\ItsAuthentication\IOAuthManager;
use SplitBill\Response\JsonResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

/**
 * Handles login via ITS account.
 *
 * @package SplitBill\Controller
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
        $userCode = $userData->getUsername();
        
    }
}
