<?php

namespace SplitBill\Email;

interface IEmailService {
    public function sendEmail($to, $subject, $viewName, $viewVars);
}
