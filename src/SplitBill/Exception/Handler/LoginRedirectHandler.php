<?php

namespace SplitBill\Exception\Handler;

use SplitBill\Exception\NotLoggedInException;
use SplitBill\Handler\IExceptionHandler;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;

class LoginRedirectHandler implements IExceptionHandler {

    /**
     * @param \Exception $exception The exception that has been thrown.
     * @param HttpRequest $request The request that resulted in the exception.
     * @return null|AbstractResponse Null if we're not going to handle it, or a response.
     */
    public function handleException(\Exception $exception, HttpRequest $request) {
        if ($exception instanceof NotLoggedInException) {
            return new RedirectResponse("http://google.com");
        }
        return null;
    }
}
