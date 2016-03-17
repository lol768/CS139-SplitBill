<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\Group;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqliteGroupRepository extends AbstractSqliteRepository implements IGroupRepository {

    protected function mapFromArray(array $results) {
        return $this->mapper->mapGroupFromArray($results);
    }

    /**
     * @param $groupId
     * @return Group
     */
    public function getById($groupId) {
        $sql = "SELECT * FROM groups WHERE groups.group_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $groupId, SQLITE3_INTEGER);
        return $this->getSingleEntityViaStatement($stmt);
    }

    /** @return Group[] */
    public function getPublicGroups() {
        $sql = "SELECT * FROM groups WHERE groups.secret = 0;";
        $stmt = $this->db->prepare($sql);
        return $this->getMultipleEntitiesViaStatement($stmt);
    }

    /**
     * @param int $userId
     * @param string|null $role
     * @return Group[]
     */
    public function getGroupsSatisfyingRelation($userId, $role) {
        if ($role == null) {
            $sql = "SELECT * FROM groups gr INNER JOIN users_groups ug ON gr.group_id = ug.group_id WHERE ug.user_id = :user_id;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
            return $this->getMultipleEntitiesViaStatement($stmt);
        }
        $sql = "SELECT * FROM groups gr INNER JOIN users_groups ug ON gr.group_id = ug.group_id WHERE ug.user_id = :user_id AND ug.role = :role;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        return $this->getMultipleEntitiesViaStatement($stmt);
    }

    /**
     * @param Group $group
     * @return Group
     */
    public function add(Group $group) {
        $sql = "INSERT INTO groups VALUES(NULL, :name, :created_at, :updated_at, :open, :secret);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $group->getName(), SQLITE3_TEXT);
        $stmt->bindValue(":created_at", $group->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $group->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":open", $group->getIsOpen() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->bindValue(":secret", $group->getIsSecret() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->execute();
        $group->setGroupId($this->db->lastInsertRowID());
        return $group;
    }

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $role
     */
    public function addRelation($groupId, $userId, $role) {
        $sql = "INSERT INTO users_groups VALUES(NULL, :user_id, :group_id, :role, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        $stmt->bindValue(":group_id", $groupId, SQLITE3_INTEGER);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        $now = new DateTime();
        $stmt->bindValue(":created_at", $now->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $now->format("U"), SQLITE3_INTEGER);
        $stmt->execute();
    }

    /**
     * @param Group $group
     * @return Group
     */
    public function update(Group $group) {
        $sql = "UPDATE groups SET name = :name, open = :open, secret = :secret, updated_at = :updated_at, created_at = :created_at WHERE group_id = :group_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":group_id", $group->getGroupId(), SQLITE3_INTEGER);
        $stmt->bindValue(":name", $group->getName(), SQLITE3_TEXT);
        $stmt->bindValue(":created_at", $group->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $group->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":open", $group->getIsOpen() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->bindValue(":secret", $group->getIsSecret() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->execute();
        $group->setGroupId($this->db->lastInsertRowID());
        return $group;
    }

    /**
     * @param Group $group
     * @return \SplitBill\Entity\Group[]
     */
    public function getRelationsForGroup(Group $group) {
        $sql = "SELECT users.*, users_groups.relation_id, users_groups.role FROM users INNER JOIN users_groups ON users_groups.user_id = users.user_id WHERE users_groups.group_id = :group_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":group_id", $group->getGroupId(), SQLITE3_INTEGER);
        $out = $stmt->execute();
        $finalObjects = array();
        while (($results = $out->fetchArray(SQLITE3_ASSOC)) !== false) {
            $finalObjects[] = $this->mapper->mapGroupRelationEntryFromArray($results);
        }
        return $finalObjects;
    }

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $role
     */
    public function addInvitation($groupId, $userId, $role) {
        $sql = "INSERT INTO invites VALUES(:group_id, :user_id, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        $stmt->bindValue(":group_id", $groupId, SQLITE3_INTEGER);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $role
     * @return bool
     */
    public function hasInvitation($groupId, $userId, $role) {
        $sql = "SELECT group_id FROM invites WHERE group_id = :group_id AND user_id = :user_id AND role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        $stmt->bindValue(":group_id", $groupId, SQLITE3_INTEGER);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        $res = $stmt->execute();
        return $res->fetchArray(SQLITE3_ASSOC) !== false;
    }
}
