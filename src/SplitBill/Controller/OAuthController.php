<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\User;
use SplitBill\Helper\IControllerHelper;
use SplitBill\ItsAuthentication\IOAuthManager;
use SplitBill\Repository\IUserRepository;
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
    /**
     * @var IUserRepository
     */
    private $userRepo;
    /**
     * @var IAuthenticationManager
     */
    private $authManager;

    public function __construct(IControllerHelper $helper, IOAuthManager $oauthManager,
                                IUserRepository $userRepo, IAuthenticationManager $authManager) {
        $this->h = $helper;
        $this->oauthManager = $oauthManager;
        $this->userRepo = $userRepo;
        $this->authManager = $authManager;
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
    public function getEndLogin($uuid, IFlashSession $flash) {
        if ($this->authManager->getEffectiveUser() !== null) {
            return $this->h->getPrettyErrorResponse("Cannot login via ITS whilst already logged in.");
        }
        $userData = $this->oauthManager->getUserInfoUsingUuid($uuid);
        $userCode = $userData->getUsername();
        $userInstance = $this->userRepo->getByItsUsername($userCode);
        if ($userInstance !== null) {
            $this->authManager->setActualUserId($userInstance->getUserId());
        } else {
            $user = new User($userData->getFullName(), $userData->getEmail(), null, true);
            $user->setItsUsername($userCode);
            $this->userRepo->add($user);
            $this->authManager->setActualUserId($user->getUserId());
            $flash->set("flash", array("message" => "New user created for ITS account $userCode", "type" => "success"));
        }
        return new RedirectResponse("index.php");
    }
}
