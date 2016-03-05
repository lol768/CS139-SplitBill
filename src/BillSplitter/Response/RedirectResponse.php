<?php

namespace BillSplitter\Response;

class RedirectResponse extends AbstractResponse {
    /**
     * @var string
     */
    private $location;

    /**
     * RedirectResponse constructor.
     * @param string $location Destination URL.
     * @param int $statusCode The HTTP response code for this response. "See Other" by default.
     */
    public function __construct($location, $statusCode=303) {
        parent::__construct($statusCode);
        $this->setHeader("Content-Type", "text/plain");
        $this->setHeader("Location", $location);
        $this->location = $location;
    }

    /**
     * The raw response body.
     */
    public function getResponseBody() {
        return "Your browser should have redirected you to " . $this->location;
    }
}
