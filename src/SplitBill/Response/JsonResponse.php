<?php

namespace SplitBill\Response;

class JsonResponse extends AbstractResponse {

    /**
     * @var array The raw data to be serialized.
     */
    private $data;

    /**
     * JsonResponse constructor.
     * @param array $data The raw data to be serialized.
     * @param int $statusCode The HTTP response code for this response.
     */
    public function __construct($data, $statusCode=200) {
        parent::__construct($statusCode);
        $this->setHeader("Content-Type", "application/json");
        $this->data = $data;
    }

    /**
     * The raw response body.
     */
    public function getResponseBody() {
        return json_encode($this->data);
    }
}
