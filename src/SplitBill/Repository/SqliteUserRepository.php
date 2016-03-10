<?php

namespace SplitBill\Repository;

use DateTime;
use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\User;
use SQLite3Stmt;

class SqliteUserRepository implements IUserRepository {

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
        return $this->getSingleUserViaStatement($stmt);
    }

    public function getById($userId) {
        $sql = "SELECT * FROM users WHERE users.user_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $userId, SQLITE3_INTEGER);
        return $this->getSingleUserViaStatement($stmt);
    }

    public function getByItsUsername($itsUsername) {
        $sql = "SELECT * FROM users WHERE users.its_username = :its_username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":its_username", $itsUsername, SQLITE3_TEXT);
        return $this->getSingleUserViaStatement($stmt);
    }

    private function mapFromArray($arr) {
        $user = new User($arr['name'], $arr['email'], $arr['password']);
        $user->setCreatedAt(DateTime::createFromFormat("U", $arr['created_at']));
        $user->setUpdatedAt(DateTime::createFromFormat("U", $arr['updated_at']));
        return $user;
    }

    /**
     * @param SQLite3Stmt $stmt
     * @return null|User
     */
    private function getSingleUserViaStatement($stmt) {
        $results = $stmt->execute();
        $results = $results->fetchArray(SQLITE3_ASSOC);
        if ($results === false) {
            return null;
        } else {
            return $this->mapFromArray($results);
        }
    }

    public function add(User $user) {
        $sql = "INSERT INTO users VALUES(NULL, :name, :email, :password, :created_at, :updated_at, :its_username);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $user->getName(), SQLITE3_TEXT);
        $stmt->bindValue(":password", $user->getPassword(), SQLITE3_TEXT);
        $stmt->bindValue(":email", $user->getEmail(), SQLITE3_TEXT);
        $stmt->bindValue(":created_at", $user->getCreatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":updated_at", $user->getUpdatedAt()->format("U"), SQLITE3_INTEGER);
        $stmt->bindValue(":its_username", $user->getItsUsername(), SQLITE3_TEXT);
        $stmt->execute();
        $user->setUserId($this->db->lastInsertRowID());
        return $user;
    }
}
