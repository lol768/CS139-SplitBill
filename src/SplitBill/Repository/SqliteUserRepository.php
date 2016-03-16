<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqliteUserRepository extends AbstractSqliteRepository implements IUserRepository {

    /**
     * @var \SQLite3
     */
    private $db;

    /**
     * SqliteUserRepository constructor.
     * @param SqliteDatabaseManager $dbm
     */
    public function __construct(SqliteDatabaseManager $dbm) {
        $this->db = $dbm->getSqlite();
    }
    
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE users.email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, SQLITE3_TEXT);
        return $this->getSingleEntityViaStatement($stmt);
    }

    public function getById($userId) {
        $sql = "SELECT * FROM users WHERE users.user_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $userId, SQLITE3_INTEGER);
        return $this->getSingleEntityViaStatement($stmt);
    }

    public function getByItsUsername($itsUsername) {
        $sql = "SELECT * FROM users WHERE users.its_username = :its_username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":its_username", $itsUsername, SQLITE3_TEXT);
        return $this->getSingleEntityViaStatement($stmt);
    }

    protected function mapFromArray(array $arr) {
        $user = new User($arr['name'], $arr['email'], $arr['password'], $arr['active'] == 1);
        $user->setUserId($arr['user_id']);
        $user->setCreatedAt(DateTime::createFromFormat("U", $arr['created_at']));
        $user->setUpdatedAt(DateTime::createFromFormat("U", $arr['updated_at']));
        $user->setItsUsername($arr['its_username']);
        return $user;
    }

    public function add(User $user) {
        $sql = "INSERT INTO users VALUES(NULL, :name, :email, :password, :created_at, :updated_at, :its_username, :active);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $user->getName(), SQLITE3_TEXT);
        $stmt->bindValue(":password", $user->getPassword(), SQLITE3_TEXT);
        $stmt->bindValue(":email", $user->getEmail(), SQLITE3_TEXT);
        $stmt->bindValue(":created_at", $user->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $user->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":its_username", $user->getItsUsername(), SQLITE3_TEXT);
        $stmt->bindValue(":active", $user->getActive() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->execute();
        $user->setUserId($this->db->lastInsertRowID());
        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user) {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password, created_at = :created_at, updated_at = :updated_at, its_username = :its_username, active = :active WHERE user_id = :user_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $user->getName(), SQLITE3_TEXT);
        $stmt->bindValue(":user_id", $user->getUserId(), SQLITE3_INTEGER);
        $stmt->bindValue(":password", $user->getPassword(), SQLITE3_TEXT);
        $stmt->bindValue(":email", $user->getEmail(), SQLITE3_TEXT);
        $stmt->bindValue(":created_at", $user->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $user->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":its_username", $user->getItsUsername(), SQLITE3_TEXT);
        $stmt->bindValue(":active", $user->getActive() ? 1 : 0, SQLITE3_INTEGER);
        $stmt->execute();
        return $user;
    }
}
