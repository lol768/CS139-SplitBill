<?php

namespace SplitBill\Repository;

use SplitBill\Entity\Group;

interface IGroupRepository {

    /** @return Group */
    public function getById($groupId);

    /** @return Group[] */
    public function getPublicGroups();

    /**
     * @param int $userId
     * @param string $role
     * @return Group[]
     */
    public function getGroupsSatisfyingRelation($userId, $role);

    /**
     * @param Group $group
     * @return Group
     */
    public function add(Group $group);

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $role
     */
    public function addRelation($groupId, $userId, $role);

    /**
     * @param Group $user
     * @return Group
     */
    public function update(Group $user);
}
