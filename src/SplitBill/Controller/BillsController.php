<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Enum\GroupRelationType;
use SplitBill\Exception\NotImplementedException;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Repository\IUserRepository;
use SplitBill\Response\JsonResponse;
use SplitBill\Session\IFlashSession;

class BillsController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IGroupRepository
     */
    private $groupRepo;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;


    public function __construct(IControllerHelper $helper, IUserRepository $userRepo, IGroupRepository $groupRepo, IAuthenticationManager $authMan) {
        $this->h = $helper;
        $this->h->requireLoggedIn();
        $this->h->setActiveNavigationItem("Bills");
        $this->groupRepo = $groupRepo;
        $this->authMan = $authMan;
    }

    /**
     * GET /bills.php
     */
    public function getDashboard() {
        $user = $this->authMan->getEffectiveUser();
        $billableGroups = array_merge($this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), GroupRelationType::ADMIN), $this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), GroupRelationType::OWNER));
        return $this->h->getViewResponse("billDashboard", array("billableGroups" => $billableGroups));
    }

}
