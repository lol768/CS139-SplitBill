<?php

namespace BillSplitter\Filter\Pre;

use BillSplitter\Request\HttpRequest;
use BillSplitter\Response\AbstractResponse;

interface IPreResponseFilter {

    /**
     * @param HttpRequest $request The request that the filter will handle.
     * @return void|AbstractResponse void to continue, AbstractResponse to halt.
     */
    public function handleRequest(HttpRequest $request);

}
