<?php

namespace SplitBill\Exception\Handler;

use SplitBill\Exception\NotLoggedInException;
use SplitBill\Handler\IExceptionHandler;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

class LoginRedirectHandler implements IExceptionHandler {
    
    /**
     * @var IFlashSession
     */
    private $flash;

    /**
     * LoginRedirectHandler constructor.
     * @param IFlashSession $flash
     */
    public function __construct(IFlashSession $flash) {
        $this->flash = $flash;
    }


    /**
     * @param \Exception $exception The exception that has been thrown.
     * @param HttpRequest $request The request that resulted in the exception.
     * @return null|AbstractResponse Null if we're not going to handle it, or a response.
     */
    public function handleException(\Exception $exception, HttpRequest $request) {
        if ($exception instanceof NotLoggedInException) {
            if ($request->getRequestMethod() === "GET") {
                $this->flash->set("previousUrl", $request->getUrlRequested());
                $this->flash->set("flash", array("type" => "warning", "message" => "You must be logged in to access that page."));
            }
            return new RedirectResponse("login.php");
        }
        return null;
    }
}
