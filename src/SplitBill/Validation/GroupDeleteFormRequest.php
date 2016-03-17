<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\Group;
use SplitBill\Entity\User;
use SplitBill\Enum\GroupRelationType;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Repository\IUserRepository;

class GroupDeleteFormRequest implements IFormRequest {

    private $errors = array();

    /**
     * @var IGroupRepository
     */
    private $groupRepo;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;
    /**
     * @var Group
     */
    private $groupToDelete;

    /**
     * GroupDeleteFormRequest constructor.
     * @param IGroupRepository $groupRepo
     * @param IAuthenticationManager $authMan
     */
    public function __construct(IGroupRepository $groupRepo, IAuthenticationManager $authMan) {
        $this->groupRepo = $groupRepo;
        $this->authMan = $authMan;
    }


    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("groupId"), $this->errors);
        if (!$this->isValid()) {
            return;
        }

        $hasPermission = false;
        $user = $this->authMan->getEffectiveUser();

        $groups = $this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), GroupRelationType::OWNER);
        foreach ($groups as $group) {
            if ($group->getGroupId() == $data['groupId']) {
                $hasPermission = true;
                $this->groupToDelete = $group;
            }
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
    public function getGroupToDelete() {
        return $this->groupToDelete;
    }
}
