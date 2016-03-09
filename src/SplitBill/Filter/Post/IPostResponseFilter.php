<?php

namespace SplitBill\Filter\Post;

use SplitBill\Response\AbstractResponse;

interface IPostResponseFilter {

    /**
     * @param AbstractResponse $response The response that the filter will handle.
     * @return AbstractResponse The modified response.
     */
    public function handleResponse(AbstractResponse $response);

}
