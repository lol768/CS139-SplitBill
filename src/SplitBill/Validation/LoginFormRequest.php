<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Entity\User;
use SplitBill\Repository\IUserRepository;

class LoginFormRequest implements IFormRequest {

    private $password;
    private $email;
    private $userInstance;

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
     * @param IUserRepository $userRepo
     */
    public function __construct(IAuthenticationManager $authManager, IUserRepository $userRepo) {
        $this->authManager = $authManager;
        $this->userRepo = $userRepo;
    }

    public function receiveFrom(array $data) {
        if ($this->authManager->getEffectiveUser() !== null) {
            $this->errors[] = "Cannot login whilst logged in.";
        }

        FormRequestUtils::requireFieldsPresent($data, array("password", "email"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) === null) {
            $this->errors[] = "Invalid email address supplied.";
        }

        if (strlen($data['password']) < 8) {
            $this->errors[] = "Password is too short.";
        }

        $this->userInstance = $this->userRepo->getByEmail($data['email']);
        if ($this->userInstance === null) {
            $this->errors[] = "No user exists with that email address.";
        } else {
            if (!$this->userInstance->getActive()) {
                $this->errors[] = "User account has not been activated.";
            }
        }

        $this->password = $data['password'];
        $this->email = $data['email'];
    }

    public function getErrors() {
        return $this->errors;
    }

    public function requiresAuthentication() {
        return false;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    /**
     * @return User
     */
    public function getPotentialUserInstance() {
        return $this->userInstance;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }
}
