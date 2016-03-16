<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Repository\IUserRepository;

class GroupAddFormRequest implements IFormRequest {

    private $name;
    private $isOpen;
    private $isSecret;

    /**
     * @var array
     */
    private $errors = array();

    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("name", "visibility", "invitationMode"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }

        if ($data['visibility'] !== "secret" && $data['visibility'] !== "public") {
            $this->errors[] = "Invalid choice for group visibility.";
        }

        if ($data['invitationMode'] !== "closed" && $data['invitationMode'] !== "open") {
            $this->errors[] = "Invalid choice for invitation mode.";
        }

        $this->name = $data['name'];
        $this->isOpen = $data['invitationMode'] === "open";
        $this->isSecret = $data['visibility'] === "secret";
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
     * @return boolean
     */
    public function isOpen() {
        return $this->isOpen;
    }

    /**
     * @return boolean
     */
    public function isSecret() {
        return $this->isSecret;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }
}
