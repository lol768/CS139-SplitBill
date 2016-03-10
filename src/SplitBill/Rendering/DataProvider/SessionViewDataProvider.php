<?php

namespace SplitBill\Rendering\DataProvider;

use SplitBill\Authentication\IAuthenticationManager;

class SessionViewDataProvider implements IViewDataProvider {
    /**
     * @var IAuthenticationManager
     */
    private $authManager;

    /**
     * SessionViewDataProvider constructor.
     * @param IAuthenticationManager $authManager
     */
    public function __construct(IAuthenticationManager $authManager) {
        $this->authManager = $authManager;
    }

    public function modifyView($viewName, &$vars) {
        $vars["user"] = $this->authManager->getEffectiveUser();
        $vars['real_user'] = $this->authManager->getRealUser();
    }
}
