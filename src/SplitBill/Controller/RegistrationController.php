<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Email\IEmailService;
use SplitBill\Entity\EmailConfirmation;
use SplitBill\Entity\User;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IEmailConfirmationRepository;
use SplitBill\Repository\IUserRepository;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Security\SecurityUtil;
use SplitBill\Session\IFlashSession;
use SplitBill\Utilities\PhpCompatibility;
use SplitBill\Validation\RegistrationFormRequest;

class RegistrationController extends AbstractController {

    /**
     * @var IUserRepository
     */
    private $userRepo;
    /**
     * @var IControllerHelper
     */
    private $h;
    /**
     * @var IAuthenticationManager
     */
    private $authManager;

    /**
     * @var IEmailConfirmationRepository
     */
    private $confirmationRepository;
    /**
     * @var HttpRequest
     */
    private $req;

    public function __construct(IControllerHelper $helper, IUserRepository $userRepo,
                                IAuthenticationManager $authManager,
                                IEmailConfirmationRepository $confirmationRepository, HttpRequest $req) {
        $this->h = $helper;
        $this->userRepo = $userRepo;
        $this->authManager = $authManager;
        $this->confirmationRepository = $confirmationRepository;
        $this->req = $req;
    }

    public function getQueryParametersForAction($action, $method) {
        if ($action === "resendemailconfirmation") {
            return array(
                "email" => array("required" => true)
            );
        } else if ($action == "confirmtoken") {
            return array(
                "token" => array("required" => true)
            );
        }
        return array();
    }


    /**
     * GET /register.php
     * @return AbstractResponse
     */
    public function getShowRegistrationForm() {
        $this->h->setActiveNavigationItem("Register");
        return $this->h->getViewResponse("registerNoModal", array());
    }

    /**
     * GET /resend_email_confirmation.php
     * @param $email
     * @param IFlashSession $flash
     * @param IEmailService $email
     * @return RedirectResponse
     */
    public function getResendEmailConfirmation($email, IFlashSession $flash, IEmailService $emailService) {
        $user = $this->userRepo->getByEmail($email);
        if ($user !== null && !$user->getActive()) {
            $this->sendEmailConfirmation($flash, $emailService, $user);
            return new RedirectResponse("email_confirm.php");
        }
        return $this->h->getPrettyErrorResponse("Provided email could not be matched with an unconfirmed account.");
    }

    /**
     * GET /email_confirm.php
     * @return AbstractResponse
     */
    public function getShowEmailSent(IFlashSession $flash) {
        $this->h->setActiveNavigationItem("Register");
        $vars = array();
        $vars['confirmationDestination'] = $flash->get("confirmation_destination");
        return $this->h->getViewResponse("emailConfirmationSent", $vars);
    }

    /**
     * GET /confirm_token.php
     * @param $token
     * @return RedirectResponse
     */
    public function getConfirmToken($token) {
        $confirmation = $this->confirmationRepository->getByToken($token);
        if ($confirmation === null) {
            return $this->h->getPrettyErrorResponse("Invalid email confirmation token.");
        }
        $userRecord = $this->userRepo->getById($confirmation->getUserId());
        if ($userRecord->getActive()) {
            return $this->h->getPrettyErrorResponse("Token has already been used.");
        }
        $userRecord->setActive(true);
        $this->userRepo->update($userRecord);
        $this->authManager->setActualUserId($userRecord->getUserId());
        return new RedirectResponse("index.php");
    }

    /**
     * POST /register.php
     * @param RegistrationFormRequest $request
     * @return AbstractResponse
     */
    public function postShowRegistrationForm(RegistrationFormRequest $request, IFlashSession $flash, IEmailService $email) {
        if (!$request->isValid()) {
            return new RedirectResponse("register.php");
        }
        $user = new User($request->getName(), $request->getEmail(), PhpCompatibility::makeBcryptHash($request->getPassword()));
        $this->userRepo->add($user);
        $this->sendEmailConfirmation($flash, $email, $user);
        return new RedirectResponse("email_confirm.php");
    }

    private function getConfirmationUrl($token) {
        $currentUrl = "http://" . $this->req->getHeader("Host") . $this->req->getUrlRequested();
        $url = preg_replace("/[A-Za-z_]+\\.php.*$/", "token_confirm.php", $currentUrl);
        return $url . "?token=$token";
    }

    /**
     * @param IFlashSession $flash
     * @param IEmailService $email
     * @param User $user
     * @throws \SplitBill\Exception\InsecureTokenGenerationException
     * @internal param RegistrationFormRequest $request
     */
    private function sendEmailConfirmation(IFlashSession $flash, IEmailService $email, User $user) {
        $confirmation = new EmailConfirmation($user->getUserId(), SecurityUtil::generateSecurityToken());
        $this->confirmationRepository->add($confirmation);

        $email->sendEmail($user->getEmail(), "SplitBill registration", "registerConfirm", array(
            "name" => $user->getName(),
            "confirmationUrl" => $this->getConfirmationUrl($confirmation->getToken())
        ));
        $flash->set("confirmation_destination", $user->getEmail());
    }
}
