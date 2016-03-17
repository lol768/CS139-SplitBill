<?php


namespace SplitBill\Repository;

use SplitBill\Database\SqliteDatabaseManager;
use SplitBill\Entity\EmailConfirmation;

class SqliteEmailConfirmationRepository extends AbstractSqliteRepository implements IEmailConfirmationRepository {
    /**
     * @param $userId
     * @return EmailConfirmation
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM email_confirmations WHERE email_confirmations.user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $userId, SQLITE3_INTEGER);
        return $this->getSingleEntityViaStatement($stmt);
    }

    /**
     * @param $token
     * @return EmailConfirmation
     */
    public function getByToken($token) {
        $sql = "SELECT * FROM email_confirmations WHERE email_confirmations.token = :token LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":token", $token, SQLITE3_TEXT);
        return $this->getSingleEntityViaStatement($stmt);
    }

    /**
     * @param EmailConfirmation $confirmation
     * @return EmailConfirmation
     */
    public function add(EmailConfirmation $confirmation) {
        $sql = "INSERT INTO email_confirmations VALUES(:user_id, :token);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $confirmation->getUserId(), SQLITE3_INTEGER);
        $stmt->bindValue(":token", $confirmation->getToken(), SQLITE3_TEXT);
        $stmt->execute();
        return $confirmation;
    }

    protected function mapFromArray(array $results) {
        $confirmationEntity = new EmailConfirmation($results['user_id'], $results['token']);
        return $confirmationEntity;
    }
}
