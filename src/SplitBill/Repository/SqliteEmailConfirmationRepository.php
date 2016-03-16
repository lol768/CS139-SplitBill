<?php


namespace SplitBill\Repository;

use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\EmailConfirmation;

class SqliteEmailConfirmationRepository implements IEmailConfirmationRepository {
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

    /**
     * @param $userId
     * @return EmailConfirmation
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM email_confirmations WHERE email_confirmations.user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        return $this->getSingleConfirmartionViaStatement($stmt);
    }

    /**
     * @param $token
     * @return EmailConfirmation
     */
    public function getByToken($token) {
        // TODO: Implement getByToken() method.
    }

    /**
     * @param EmailConfirmation $confirmation
     * @return EmailConfirmation
     */
    public function add(EmailConfirmation $confirmation) {
        // TODO: Implement add() method.
    }

    private function getSingleConfirmartionViaStatement($stmt) {
    }
}
