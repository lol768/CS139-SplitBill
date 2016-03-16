<?php

namespace SplitBill\Rendering\DataProvider;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\Security\IAntiRequestForgery;

class AuthViewDataProvider implements IViewDataProvider {
    /**
     * @var IAuthenticationManager
     */
    private $authManager;
    /**
     * @var IAntiRequestForgery
     */
    private $antiRequestForgery;

    /**
     * AuthViewDataProvider constructor.
     * @param IAuthenticationManager $authManager
     * @param IAntiRequestForgery $antiRequestForgery
     */
    public function __construct(IAuthenticationManager $authManager, IAntiRequestForgery $antiRequestForgery) {
        $this->authManager = $authManager;
        $this->antiRequestForgery = $antiRequestForgery;
    }

    public function modifyView($viewName, &$vars) {
        $vars["user"] = $this->authManager->getEffectiveUser();
        $vars['real_user'] = $this->authManager->getRealUser();
        $vars['csrfToken'] = $this->antiRequestForgery->getCurrentCsrfToken();
    }
}
