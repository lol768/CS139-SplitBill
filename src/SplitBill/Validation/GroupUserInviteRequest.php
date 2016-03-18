<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\Group;
use SplitBill\Entity\User;
use SplitBill\Enum\GroupRelationType;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Repository\IUserRepository;

class GroupUserInviteRequest implements IFormRequest {

    private $errors = array();

    /**
     * @var IGroupRepository
     */
    private $groupRepo;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;

    private $userToInvite;
    /** @var Group */
    private $group;
    private $role;
    /**
     * @var IUserRepository
     */
    private $userRepo;

    /**
     * GroupUserInviteRequest constructor.
     * @param IGroupRepository $groupRepo
     * @param IAuthenticationManager $authMan
     */
    public function __construct(IGroupRepository $groupRepo, IAuthenticationManager $authMan, IUserRepository $userRepo) {
        $this->groupRepo = $groupRepo;
        $this->authMan = $authMan;
        $this->userRepo = $userRepo;
    }


    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("selectedId", "role", "groupId"), $this->errors);
        if (!$this->isValid()) {
            return;
        }
        if ($data['role'] !== GroupRelationType::ADMIN && $data['role'] !== GroupRelationType::MEMBER) {
            $this->errors[] = "Invalid role selection";
        }
        $this->role = $data['role'];

        $hasPermission = false;
        $user = $this->authMan->getEffectiveUser();
        if ($user->getUserId() == $data['selectedId']) {
            $this->errors[] = "You can't invite yourself to a group";
        }


        $selectedUser = $this->userRepo->getById($data['selectedId']);
        if ($selectedUser === null) {
            $this->errors[] = "Invalid user selection for invite.";
        }
        $this->userToInvite = $selectedUser;

        $groups = $this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), GroupRelationType::OWNER);
        foreach ($groups as $group) {
            if ($group->getGroupId() == $data['groupId']) {
                $hasPermission = true;
                $this->group = $group;
            }
        }

        if ($this->groupRepo->hasAnyRelation($this->group->getGroupId(), $selectedUser->getUserId())) {
            $this->errors[] = "User is already a part of this group.";
        }

        if (!$hasPermission) {
            $this->errors[] = "Couldn't find a valid group you own with that ID.";
        }
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }

    public function requiresAuthentication() {
        return true;
    }

    /**
     * @return Group
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * @return User
     */
    public function getUserToInvite() {
        return $this->userToInvite;
    }

    /**
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

}
