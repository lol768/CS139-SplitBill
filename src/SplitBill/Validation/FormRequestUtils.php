<?php

namespace SplitBill\Validation;

class FormRequestUtils {

    public static function requireFieldsPresent(array $data, array $fields, array &$errors) {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === "") {
                $errors[] = "The " . $field . " field was missing.";
            }
        }
    }

}
