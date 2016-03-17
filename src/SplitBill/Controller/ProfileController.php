<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IUserRepository;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;
use SplitBill\Utilities\PhpCompatibility;
use SplitBill\Validation\ChangePasswordFormRequest;
use SplitBill\Validation\ProfileEditFormRequest;

class ProfileController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    /**
     * @var IContainer
     */
    private $container;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;
    /**
     * @var IUserRepository
     */
    private $userRepo;

    public function __construct(IControllerHelper $helper, IContainer $container, IAuthenticationManager $authMan, IUserRepository $userRepo) {
        $this->h = $helper;
        $this->container = $container;
        $this->h->requireLoggedIn();
        $this->authMan = $authMan;
        $this->userRepo = $userRepo;
    }

    /**
     * GET /edit_profile.php
     */
    public function getShowEditProfile() {
        return $this->h->getViewResponse("editProfile", array(
            "title" => "Edit profile"
        ));
    }

    public function postShowEditProfile(ProfileEditFormRequest $request, IFlashSession $flash) {
        if ($request->isValid()) {
            $user = $this->authMan->getEffectiveUser();
            $user->setName($request->getName());
            $user->setEmail($request->getEmail());
            $this->userRepo->update($user);
            $flash->set("flash", array("message" => "Profile details updated successfully", "type" => "success"));
        }

        return new RedirectResponse("edit_profile.php");
    }

    public function postChangePassword(ChangePasswordFormRequest $request, IFlashSession $flash) {
        if ($request->isValid()) {
            $user = $this->authMan->getEffectiveUser();
            $user->setPassword(PhpCompatibility::makeBcryptHash($user->getPassword()));
            $this->userRepo->update($user);
            $flash->set("flash", array("message" => "Password updated!", "type" => "success"));
        }

        return new RedirectResponse("edit_profile.php");
    }

}
