<?php

namespace SplitBill\Handler;

use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;

interface IExceptionHandler {

    /**
     * @param \Exception $exception The exception that has been thrown.
     * @param HttpRequest $request The request that resulted in the exception.
     * @return null|AbstractResponse Null if we're not going to handle it, or a response.
     */
    public function handleException(\Exception $exception, HttpRequest $request);
}
