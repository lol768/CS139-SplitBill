<?php

namespace SplitBill\Filter\Post;

use SplitBill\Response\AbstractResponse;

class ContentSecurityPolicy implements IPostResponseFilter  {

    /**
     * Increases security by over 9000.
     *
     * @param AbstractResponse $response
     * @return AbstractResponse
     */
    public function handleResponse(AbstractResponse $response) {
        $response->setHeader("Content-Security-Policy",
            "default-src 'self'; style-src 'self' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; script-src 'self' https://cdnjs.cloudflare.com; connect-src 'self' ws://americano.adamwilliams.host:8765;"
        );
        return $response;
    }
}
