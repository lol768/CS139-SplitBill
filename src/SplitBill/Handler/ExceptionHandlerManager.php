<?php

namespace SplitBill\Handler;

use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;

class ExceptionHandlerManager implements IExceptionHandlerManager {

    /**
     * @var IExceptionHandler[]
     */
    private $handlers = array();

    /**
     * Registers a new view data provider to use when rendering views.
     *
     * @param IExceptionHandler $handler The handler to register.
     */
    public function registerExceptionHandler(IExceptionHandler $handler) {
        $this->handlers[] = $handler;
    }

    /**
     * @param \Exception $exception The exception that has been thrown and will be handled.
     * @param HttpRequest $request The request that resulted in the throwing of the aforementioned exception.
     * @return null|AbstractResponse The response, if any, from the registered handlers.
     */
    public function handleExceptionUsingRegisteredHandlers(\Exception $exception, HttpRequest $request) {
        foreach ($this->handlers as $handler) {
            $response = $handler->handleException($exception, $request);
            if ($response !== null) {
                // fail fast out of here
                return $response;
            }
        }
        return null; // no handlers handled the exception
    }
}
