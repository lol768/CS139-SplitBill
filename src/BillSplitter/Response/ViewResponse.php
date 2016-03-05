<?php

namespace BillSplitter\Response;

class ViewResponse extends AbstractResponse {

    /**
     * @var string The name of the view we're rendering.
     */
    private $viewName;

    /**
     * @var string The response body.
     */
    private $body;

    /**
     * ViewResponse constructor.
     * @param int $viewName
     * @param int $statusCode The HTTP response code for this response.
     */
    public function __construct($viewName, $statusCode=200) {
        parent::__construct($statusCode);
        $this->viewName = $viewName;
    }

    /**
     * The raw response body.
     */
    public function getResponseBody() {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setResponseBody($body) {
        $this->body = $body;
    }
}
