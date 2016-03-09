<?php

namespace SplitBill\Response;

class RawResponse extends AbstractResponse {

    /**
     * @var array The raw data to be dumped on the page.
     */
    private $data;

    /**
     * RawResponse constructor.
     * @param array $data The raw data to be dumped on the page.
     * @param string $contentType The content type, e.g. text/plain.
     * @param int $statusCode The HTTP response code for this response.
     */
    public function __construct($data, $contentType, $statusCode=200) {
        parent::__construct($statusCode);
        $this->setHeader("Content-Type", $contentType);
        $this->data = $data;
    }

    /**
     * The raw response body.
     */
    public function getResponseBody() {
        return $this->data;
    }
}
