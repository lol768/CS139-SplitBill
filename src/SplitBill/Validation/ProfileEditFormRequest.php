<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Repository\IUserRepository;

class ProfileEditFormRequest implements IFormRequest {

    private $name;
    private $email;

    /**
     * @var array
     */
    private $errors = array();

    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("name", "email"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) === null) {
            $this->errors[] = "Invalid email address supplied.";
        }

        $this->name = $data['name'];
        $this->email = $data['email'];
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
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }
}
