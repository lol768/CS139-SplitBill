<?php

namespace SplitBill\Email;

use SplitBill\Rendering\IViewRenderer;
use SplitBill\Security\SecurityUtil;

class DummyEmailService implements IEmailService {

    /**
     * @var IViewRenderer
     */
    private $viewRenderer;

    /**
     * DummyEmailService constructor.
     * @param IViewRenderer $viewRenderer
     */
    public function __construct(IViewRenderer $viewRenderer) {
        $this->viewRenderer = $viewRenderer;
    }

    public function sendEmail($to, $subject, $viewName, $viewVars) {
        $viewName = "emails/" . $viewName;
        $plainViewName = $viewName . "Plain";

        $messageBody = "Email to $to with subject $subject:\n\n";
        $messageBody .= "Plain Text:\n";
        $messageBody .= $this->viewRenderer->renderView($plainViewName, $viewVars);
        $messageBody .= "\n\nHTML:\n" . $this->viewRenderer->renderView($viewName, $viewVars);

        file_put_contents("/tmp/email_" . SecurityUtil::generateSecurityToken(10) . ".txt", $messageBody);
    }
}
