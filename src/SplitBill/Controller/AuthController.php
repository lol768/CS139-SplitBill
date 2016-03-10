<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\User;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IUserRepository;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Security\SecurityUtil;
use SplitBill\Session\IFlashSession;
use SplitBill\Utilities\PhpCompatibility;
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

    /**
     * GET /register.php
     */
    public function getShowRegistrationForm(HttpRequest $req) {
        $this->h->setActiveNavigationItem("Register");
        return $this->h->getViewResponse("registerNoModal", array());
    }

    /**
     * GET /login.php
     */
    public function getShowLoginForm(HttpRequest $req) {
        $this->h->setActiveNavigationItem("Login");
        return $this->h->getViewResponse("loginNoModal", array());
    }

    /**
     * POST /register.php
     * @param RegistrationFormRequest $request
     * @return AbstractResponse
     */
    public function postShowRegistrationForm(RegistrationFormRequest $request) {
        if (!$request->isValid()) {
            return new RedirectResponse("register.php");
        }
        $user = new User($request->getName(), $request->getEmail(), PhpCompatibility::makeBcryptHash($request->getPassword()));
        $this->authManager->setActualUserId($this->userRepo->add($user)->getUserId());
        return new RedirectResponse("index.php");
    }

}
