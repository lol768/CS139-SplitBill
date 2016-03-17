<?php

namespace SplitBill\Database;

use DateTime;
use SplitBill\Entity\Bill;
use SplitBill\Entity\Group;
use SplitBill\Entity\GroupRelationEntry;
use SplitBill\Entity\User;

class SqliteEntityMapper implements IEntityMapper {

    /**
     * @param array $data
     * @return User
     */
    public function mapUserFromArray(array $data) {
        $user = new User($data['name'], $data['email'], $data['password'], $data['active'] == 1);
        $user->setUserId($data['user_id']);
        $user->setCreatedAt(DateTime::createFromFormat("U", $data['created_at']));
        $user->setUpdatedAt(DateTime::createFromFormat("U", $data['updated_at']));
        $user->setItsUsername($data['its_username']);
        $user->setHasAvatar($data['has_avatar'] == 1);
        return $user;
    }

    /**
     * @param array $data
     * @return Group
     */
    public function mapGroupFromArray(array $data) {
        $group = new Group($data['name'], $data['open'] == 1, $data['secret'] == 1);
        $group->setCreatedAt(DateTime::createFromFormat("U", $data['created_at']));
        $group->setUpdatedAt(DateTime::createFromFormat("U", $data['updated_at']));
        $group->setGroupId($data['group_id']);
        return $group;
    }

    /**
     * @param array $data
     * @return GroupRelationEntry
     */
    public function mapGroupRelationEntryFromArray(array $data) {
        $user = $this->mapUserFromArray($data);
        $relation = new GroupRelationEntry($data['relation_id'], $data['role'], $user);
        return $relation;
    }

    /**
     * @param array $data
     * @return Bill
     */
    public function mapBillFromArray(array $data) {
        $bill = new Bill($data['user_id'], $data['group_id'], $data['amount'], $data['description'], $data['company']);
        $bill->setCreatedAt(DateTime::createFromFormat("U", $data['created_at']));
        $bill->setUpdatedAt(DateTime::createFromFormat("U", $data['updated_at']));
        $bill->setBillId($data['bill_id']);
        return $bill;
    }
}
