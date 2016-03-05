<?php


namespace BillSplitter\Response;


abstract class AbstractResponse {

    /**
     * @var int The HTTP response code for this response.
     */
    private $statusCode;

    /**
     * @var array Associative string=>string array of response headers.
     */
    private $headerMap = array();

    /**
     * AbstractResponse constructor.
     * @param int $statusCode
     */
    protected function __construct($statusCode) {
        $this->statusCode = $statusCode;
    }

    /**
     * Sets the value of a response header item.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     */
    public function setHeader($name, $value) {
        $this->headerMap[$name] = $value;
    }

    /**
     * @param string $name The name of the
     */
    public function getHeader($name) {
        return $this->headerMap[$name];
    }

    /**
     * @@return array Associative array of all headers to be set for this response.
     */
    public function getAllHeaders() {
        return $this->headerMap;
    }

    public abstract function getResponseBody();

    /**
     * @return int HTTP status code.
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode HTTP status code.
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
    }

}
