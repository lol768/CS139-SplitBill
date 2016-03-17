<?php

namespace SplitBill\Exception\Handler;

use SplitBill\Exception\NotImplementedException;
use SplitBill\Handler\IExceptionHandler;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

class NotImplementedHandler implements IExceptionHandler {
    
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
        if ($exception instanceof NotImplementedException) {
            $this->flash->set("flash", array("type" => "error", "message" => "That functionality is not yet implemented"));
            return new RedirectResponse("index.php");
        }
        return null;
    }
}
