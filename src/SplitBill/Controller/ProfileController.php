<?php

namespace SplitBill\Controller;

use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Session\IFlashSession;

class ProfileController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    /**
     * @var IContainer
     */
    private $container;

    public function __construct(IControllerHelper $helper, IContainer $container) {
        $this->h = $helper;
        $this->container = $container;
        $this->h->requireLoggedIn();
    }

    /**
     * GET /edit_profile.php
     */
    public function getShowEditProfile() {
        return $this->h->getViewResponse("editProfile", array(
            "title" => "Edit profile"
        ));
    }

}
