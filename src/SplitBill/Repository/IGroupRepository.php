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
     * @param string|null $role
     * @return Group[]
     */
    public function getGroupsSatisfyingRelation($userId, $role);

    /**
     * @param Group $group
     * @return \SplitBill\Entity\GroupRelationEntry[]
     */
    public function getRelationsForGroup(Group $group);

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
     * @param int $groupId
     * @param int $userId
     * @param string $role
     */
    public function addInvitation($groupId, $userId, $role);

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $role
     * @return bool
     */
    public function hasInvitation($groupId, $userId, $role);

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $role
     * @return bool
     */
    public function hasRelation($groupId, $userId, $role);

    /**
     * @param int $groupId
     * @param int $userId
     * @return bool
     */
    public function hasAnyRelation($groupId, $userId);

    /**
     * @param Group $user
     * @return Group
     */
    public function update(Group $user);
}
