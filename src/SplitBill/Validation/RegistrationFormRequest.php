<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Repository\IUserRepository;

class RegistrationFormRequest implements IFormRequest {

    private $name;
    private $password;
    private $email;

    /**
     * @var array
     */
    private $errors = array();

    /**
     * @var IAuthenticationManager
     */
    private $authManager;
    /**
     * @var IUserRepository
     */
    private $userRepo;

    /**
     * RegistrationFormRequest constructor.
     * @param IAuthenticationManager $authManager
     */
    public function __construct(IAuthenticationManager $authManager, IUserRepository $userRepo) {
        $this->authManager = $authManager;
        $this->userRepo = $userRepo;
    }

    public function receiveFrom(array $data) {
        if ($this->authManager->getEffectiveUser() !== null) {
            $this->errors[] = "Cannot register whilst logged in.";
        }

        FormRequestUtils::requireFieldsPresent($data, array("name", "password", "email"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) === null) {
            $this->errors[] = "Invalid email address supplied.";
        }

        if (strlen($data['password']) < 8) {
            $this->errors[] = "Password is too short.";
        }

        if ($this->userRepo->getByEmail($data['email']) !== null) {
            $this->errors[] = "Email address is already in use.";
        }

        $this->name = $data['name'];
        $this->password = $data['password'];
        $this->email = $data['email'];
    }

    public function getErrors() {
        return $this->errors;
    }

    public function requiresAuthentication() {
        return false;
    }

    public function getName() {
        return $this->name;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }
}
