<?php

namespace SplitBill\Validation;

interface IFormRequest {

    public function receiveFrom(array $data);

    /**
     * @return array
     */
    public function getErrors();

    public function isValid();
    
    public function requiresAuthentication();
}
