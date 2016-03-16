<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\EmailConfirmation;
use SplitBill\Entity\User;
use SplitBill\Exception\CsrfException;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IUserRepository;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Security\IAntiRequestForgery;
use SplitBill\Security\SecurityUtil;
use SplitBill\Session\IFlashSession;
use SplitBill\Utilities\PhpCompatibility;
use SplitBill\Validation\LoginFormRequest;
use SplitBill\Validation\RegistrationFormRequest;

class AuthController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IUserRepository
     */
    private $userRepo;
    /**
     * @var IAuthenticationManager
     */
    private $authManager;

    public function __construct(IControllerHelper $helper, IUserRepository $userRepo, IAuthenticationManager $authManager) {
        $this->h = $helper;
        $this->userRepo = $userRepo;
        $this->authManager = $authManager;
    }

    public function getQueryParametersForAction($action, $method) {
        if ($action === "logout") {
            return array("csrf" => array("required" => true));
        }
        return array();
    }


    /**
     * GET /login.php
     */
    public function getShowLoginForm(HttpRequest $req, IFlashSession $flash) {
        $this->h->setActiveNavigationItem("Login");
        $flash->extendLife("previousUrl");
        return $this->h->getViewResponse("loginNoModal", array());
    }

    /**
     * POST /login.php
     */
    public function postShowLoginForm(LoginFormRequest $loginRequest, IFlashSession $flash) {
        $flash->extendLife("previousUrl");

        if (!$loginRequest->isValid()) {
            return new RedirectResponse("login.php");
        }

        $bcryptedPassword = $loginRequest->getPotentialUserInstance()->getPassword();
        if (!PhpCompatibility::checkBcryptHash($bcryptedPassword, $loginRequest->getPassword())) {
            $flash->set("errors", array("Invalid password."));
            $flash->set("oldData", array("email" => $loginRequest->getEmail()));
            return new RedirectResponse("login.php");
        }
        $this->authManager->setActualUserId($loginRequest->getPotentialUserInstance()->getUserId());
        return new RedirectResponse($flash->getOrDefault("previousUrl", "index.php"));
    }

    /**
     * GET /logout.php
     *
     * @param $csrf
     * @param IAntiRequestForgery $antiRequestForgery
     * @return AbstractResponse
     * @throws CsrfException
     */
    public function getLogout($csrf, IAntiRequestForgery $antiRequestForgery) {
        if (!SecurityUtil::timingSafeComparison($antiRequestForgery->getCurrentCsrfToken(), $csrf)) {
            throw new CsrfException("Logout CSRF detected");
        }

        $this->authManager->logout();
        return new RedirectResponse("index.php");
    }

}
