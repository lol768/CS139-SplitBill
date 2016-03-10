<?php

namespace SplitBill\Authentication;

use SplitBill\Entity\User;
use SplitBill\Exception\InvalidUserException;
use SplitBill\Repository\IUserRepository;
use SplitBill\Session\IUserSession;

class SessionAuthenticationManager implements IAuthenticationManager {

    /**
     * @var IUserRepository The user repository instance.
     */
    private $userRepository;

    /**
     * @var IUserSession The user session instance.
     */
    private $userSession;

    /**
     * @var User Cached effective user instance.
     */
    private $effectiveUser = null;

    /**
     * @var User Cached real user instance.
     */
    private $realUser = null;

    /**
     * SessionAuthenticationManager constructor.
     * @param IUserRepository $userRepository
     * @param IUserSession $userSession
     */
    public function __construct(IUserRepository $userRepository, IUserSession $userSession) {
        $this->userRepository = $userRepository;
        $this->userSession = $userSession;
        $this->initializeFromSession();
    }

    public function getEffectiveUser() {
        return $this->effectiveUser;
    }

    public function getRealUser() {
        return $this->realUser;
    }

    public function setActualUserId($userId) {
        $instance = $this->userRepository->getById($userId);
        if ($instance === null) {
            throw new InvalidUserException("Provided ID does not exist.");
        }
        $this->userSession->set("user_id", $userId);
    }

    public function masquerade($userId) {
        $instance = $this->userRepository->getById($userId);
        if ($instance === null) {
            throw new InvalidUserException("Provided ID does not exist.");
        }
        if ($this->realUser === null || $this->realUser->getUserId() === $instance->getUserId()) {
            throw new InvalidUserException("Cannot masquerade as this user.");
        }
        $this->effectiveUser = $instance;
        $this->userSession->set("masquerading_as_user_id", $userId);
    }

    public function unmasquerade() {
        $this->userSession->remove("masquerading_as_user_id");
        $this->effectiveUser = $this->realUser;
    }

    private function initializeFromSession() {
        if ($this->userSession->has("user_id")) {
            $this->realUser = $this->userRepository->getById($this->userSession->get("user_id"));
            $this->effectiveUser = $this->realUser;
        }

        if ($this->userSession->has("masquerading_as_user_id")) {
            $this->effectiveUser = $this->userRepository->getById($this->userSession->get("masquerading_as_user_id"));
        }
    }
}
