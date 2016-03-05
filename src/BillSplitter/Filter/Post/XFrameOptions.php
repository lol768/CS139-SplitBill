<?php

namespace BillSplitter\Filter\Post;

use BillSplitter\Response\AbstractResponse;

class XFrameOptions implements IPostResponseFilter  {

    /**
     * Disallow framing
     *
     * @param AbstractResponse $response
     * @return AbstractResponse
     */
    public function handleResponse(AbstractResponse $response) {
        $response->setHeader("X-Frame-Options",
            "DENY"
        );
        return $response;
    }
}
