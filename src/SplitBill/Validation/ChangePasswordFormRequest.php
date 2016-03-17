<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Repository\IUserRepository;
use SplitBill\Utilities\PhpCompatibility;

class ChangePasswordFormRequest implements IFormRequest {

    private $newPassword;

    /**
     * @var array
     */
    private $errors = array();
    /**
     * @var IAuthenticationManager
     */
    private $authMan;

    /**
     * ChangePasswordFormRequest constructor.
     */
    public function __construct(IAuthenticationManager $authMan) {
        $this->authMan = $authMan;
    }

    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("new_password", "new_password_confirm", "current_password"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }

        if (strlen($data['new_password']) < 8) {
            $this->errors[] = "New password is too short.";
        }

        $user = $this->authMan->getEffectiveUser();
        $password = $user->getPassword(); // bcrypt'd

        if (!PhpCompatibility::checkBcryptHash($password, $data['current_password'])) {
            $this->errors[] = "Incorrect existing password.";
        }

        if ($data['new_password'] !== $data['new_password_confirm']) {
            $this->errors[] = "New password confirmation is incorrect";
        }

        $this->newPassword = $data['new_password'];
    }

    public function getErrors() {
        return $this->errors;
    }

    public function requiresAuthentication() {
        return true;
    }

    /**
     * @return string
     */
    public function getNewPassword() {
        return $this->newPassword;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }
}
