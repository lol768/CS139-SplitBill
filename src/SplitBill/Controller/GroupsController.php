<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\Group;
use SplitBill\Entity\User;
use SplitBill\Enum\GroupRelationType;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Validation\GroupAddFormRequest;

class GroupsController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IGroupRepository
     */
    private $groupRepo;
    /** @var User */
    private $user;

    public function __construct(IControllerHelper $helper, IGroupRepository $groupRepo, IAuthenticationManager $authMan) {
        $this->h = $helper;
        $this->h->requireLoggedIn();
        $this->h->setActiveNavigationItem("Groups");
        $this->groupRepo = $groupRepo;
        $this->user = $authMan->getRealUser();
    }

    /**
     * GET /groups.php
     */
    public function getGroupsList() {
        $myGroups = $this->groupRepo->getGroupsSatisfyingRelation($this->user->getUserId(), GroupRelationType::OWNER);
        return $this->h->getViewResponse("groupsList", array(
            "title" => "Groups",
            "myGroups" => $myGroups
        ));
    }

    /**
     * POST /add_group.php
     * @param GroupAddFormRequest $groupAdd
     * @return AbstractResponse
     */
    public function postAddGroup(GroupAddFormRequest $groupAdd) {
        if (!$groupAdd->isValid()) {
            return new RedirectResponse("groups.php");
        } else {
            $group = new Group($groupAdd->getName(), $groupAdd->isSecret(), $groupAdd->isOpen());
            $this->groupRepo->add($group);
            $this->groupRepo->addRelation($group->getGroupId(), $this->user->getUserId(), GroupRelationType::OWNER);
            return new RedirectResponse("groups.php");
        }
    }

}
