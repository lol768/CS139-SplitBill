<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\Group;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqliteGroupRepository extends AbstractSqliteRepository implements IGroupRepository {

    /**
     * @var \SQLite3
     */
    private $db;

    /**
     * SqliteGroupRepository constructor.
     * @param SqliteDatabaseManager $dbm
     */
    public function __construct(SqliteDatabaseManager $dbm) {
        $this->db = $dbm->getSqlite();
    }

    protected function mapFromArray(array $results) {
        $group = new Group($results['name'], $results['open'] == 1, $results['secret'] == 1);
        $group->setCreatedAt(DateTime::createFromFormat("U", $results['created_at']));
        $group->setUpdatedAt(DateTime::createFromFormat("U", $results['updated_at']));
        $group->setGroupId($results['group_id']);
        return $group;
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
        $stmt->execute();
        return $this->getMultipleEntitiesViaStatement($stmt);
    }

    /**
     * @param int $userId
     * @param string $role
     * @return Group[]
     */
    public function getGroupsSatisfyingRelation($userId, $role) {
        $sql = "SELECT * FROM groups gr INNER JOIN users_groups ug ON gr.group_id = ug.group_id WHERE ug.user_id = :user_id AND ug.role = :role;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        $stmt->execute();
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
}
