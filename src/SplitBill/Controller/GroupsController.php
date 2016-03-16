<?php

namespace SplitBill\Controller;

use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Session\IFlashSession;

class GroupsController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    public function __construct(IControllerHelper $helper) {
        $this->h = $helper;
        $this->h->requireLoggedIn();
        $this->h->setActiveNavigationItem("Groups");
    }

    /**
     * GET /groups.php
     */
    public function getGroupsList() {
        return $this->h->getViewResponse("groupsList", array(
            "title" => "Groups"
        ));
    }

}
