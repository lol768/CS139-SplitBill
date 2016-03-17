<?php

namespace SplitBill\Validation;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Repository\IUserRepository;

class MasqueradeFormRequest implements IFormRequest {

    private $uid;

    /**
     * @var array
     */
    private $errors = array();

    public function receiveFrom(array $data) {
        FormRequestUtils::requireFieldsPresent($data, array("uid"), $this->errors);
        if (count($this->errors) > 0) {
            return;
        }

       $this->uid = $data['uid'];
    }

    public function getErrors() {
        return $this->errors;
    }

    public function requiresAuthentication() {
        return true;
    }

    /**
     * @return mixed
     */
    public function getUid() {
        return $this->uid;
    }

    public function isValid() {
        return count($this->errors) === 0;
    }
}
