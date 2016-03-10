<?php

namespace SplitBill\Email;

use SplitBill\Rendering\IViewRenderer;

class MultipartEmailService implements IEmailService {

    /**
     * @var IViewRenderer
     */
    private $viewRenderer;

    /**
     * @var string The multipart boundary
     */
    private $boundary;

    /**
     * MultipartEmailService constructor.
     * @param IViewRenderer $viewRenderer
     */
    public function __construct(IViewRenderer $viewRenderer) {
        $this->viewRenderer = $viewRenderer;
        $this->boundary = $this->generateBoundary();
    }

    public function sendEmail($to, $subject, $viewName, $viewVars) {
        $viewName = "emails/" . $viewName;
        $plainViewName = $viewName . "Plain";
        $messageBody = $this->getEncodedView($plainViewName, $viewVars, "text/plain");
        $messageBody .= "\r\n\r\n" . $this->getEncodedView($viewName, $viewVars, "text/html");
        $messageBody .= rtrim("\r\n\r\n" . $this->getBoundaryString()) . "--";
        mail($to, $subject, $messageBody, $this->getHeadersAsString($this->getHeaders()));
    }

    private function getEncodedView($viewName, $viewVars, $contentType) {
        $str = $this->getBoundaryString();
        $str .= $this->getHeadersAsString(array(
                "Content-Type" => "$contentType; charset=\"UTF-8\"",
                "Content-Transfer-Encoding" => "8bit"
            )) . "\r\n";
        $str .= rtrim($this->viewRenderer->renderView($viewName, $viewVars));
        return $str;
    }

    private function getHeaders() {
        return array(
            "MIME-Version" => "1.0",
            "Content-Type" => "multipart/alternative; boundary=" . $this->boundary,
        );
    }

    private function getHeadersAsString(array $headers) {
        $str = "";
        foreach ($headers as $key => $value) {
            $str .= "$key: $value\r\n";
        }
        return $str;
    }

    private function generateBoundary() {
        return sha1(uniqid("SplitBill"));
    }

    /**
     * @return string
     */
    private function getBoundaryString() {
        return "--{$this->boundary}\r\n";
    }
}
