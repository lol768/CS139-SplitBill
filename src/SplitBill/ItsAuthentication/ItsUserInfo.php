<?php

namespace SplitBill\ItsAuthentication;

class ItsUserInfo {

    private $email;
    private $username;
    private $departmentName;
    private $departmentCode;
    private $firstName;
    private $lastName;
    private $fullName;
    private $userType;

    /**
     * Holds (some of the) information passed back to us by IT Services.
     *
     * @param string $email The user's Warwick email address (x@warwick.ac.uk)
     * @param string $username The user's ITS username. E.g. u1510654
     * @param string $departmentName The full name of the user's department at the university (e.g. "Computer Science").
     * @param string $departmentCode The short name of the user's department (e.g CS)
     * @param string $firstName The user's first name.
     * @param string $lastName The user's last name.
     * @param string $fullName The user's full name.
     * @param string $userType Text representing the type of user (e.g "Student")
     */
    public function __construct($email, $username, $departmentName, $departmentCode, $firstName, $lastName, $fullName, $userType) {
        $this->email = $email;
        $this->username = $username;
        $this->departmentName = $departmentName;
        $this->departmentCode = $departmentCode;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->fullName = $fullName;
        $this->userType = $userType;
    }

    /**
     * @return string The user's Warwick email address (x@warwick.ac.uk)
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string The user's ITS username. E.g. u1510654
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return string The full name of the user's department at the university (e.g. "Computer Science").
     */
    public function getDepartmentName() {
        return $this->departmentName;
    }

    /**
     * @return string The short name of the user's department (e.g CS)
     */
    public function getDepartmentCode() {
        return $this->departmentCode;
    }

    /**
     * @return string The user's first name.
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @return string The user's last name.
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @return string The user's full name.
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * @return string Text representing the type of user (e.g "Student")
     */
    public function getUserType() {
        return $this->userType;
    }




}
