<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\Group;
use SplitBill\Enum\GroupRelationType;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Repository\IUserRepository;

class BillCreationFormRequest implements IFormRequest {

    private $company;
    private $amount;
    private $description;
    private $group;


    /**
     * @var array
     */
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
     * BillCreationFormRequest constructor.
     * @param IGroupRepository $groupRepo
     * @param IAuthenticationManager $authMan
     */
    public function __construct(IGroupRepository $groupRepo, IAuthenticationManager $authMan) {
        $this->groupRepo = $groupRepo;
        $this->authMan = $authMan;
    }

    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("company", "description", "amount", "group_id"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }
        $user = $this->authMan->getEffectiveUser();
        $data['amount'] = str_replace("£", "", $data['amount']);

        if ($data['amount'] <= 0 || !preg_match("/^[0-9]+(\\.[0-9]{1,2})?$/", $data['amount'])) {
            $this->errors[] = "Invalid amount, " . $data['amount'] . ".";
        }
        $hasPermission = false;
        $groups = $this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), null);
        foreach ($groups as $group) {
            if ($group->getGroupId() == $data['group_id'] &&
                $this->groupRepo->hasRelation($group->getGroupId(), $user->getUserId(), GroupRelationType::ADMIN) ||
                $this->groupRepo->hasRelation($group->getGroupId(), $user->getUserId(), GroupRelationType::OWNER)) {
                $hasPermission = true;
                $this->group = $group;
            }
        }

        if (!$hasPermission) {
            $this->errors[] = "You can't add bills to this group.";
        }

        $this->company = $data['company'];
        $this->amount = str_replace(".", "", $data['amount']);
        $this->description = $data['description'];
    }

    public function getErrors() {
        return $this->errors;
    }

    public function requiresAuthentication() {
        return true;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }

    /**
     * @return string
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * @return int
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return Group
     */
    public function getGroup() {
        return $this->group;
    }
}
