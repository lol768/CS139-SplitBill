<?php

namespace SplitBill\Request;

/**
 * Need to store: URL, headers, request method.
 * Maybe convenient getters for $_POST, $_SESSION etc etc
 */
class HttpRequest {

    private $requestMethod;
    private $headers;
    private $urlRequested;
    private $queryStringParameters;
    private $formData;
    private $host;

    /**
     * HttpRequest constructor.
     * @param $requestMethod
     * @param $headers
     * @param $urlRequested
     * @param $queryStringParameters
     * @param $formData
     * @param $host
     */
    public function __construct($requestMethod, $headers, $urlRequested, $queryStringParameters, $formData, $host) {
        $this->requestMethod = $requestMethod;
        $this->headers = $headers;
        $this->urlRequested = $urlRequested;
        $this->queryStringParameters = $queryStringParameters;
        $this->formData = $formData;
        $this->host = $host;
    }

    /**
     * @return string The host requested. Keep in mind the user can spoof this.
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string The HTTP request method (e.g. "GET" or "POST")
     */
    public function getRequestMethod() {
        return $this->requestMethod;
    }

    /**
     * @return array
     */
    public function getUploadedFiles() {
        return $_FILES;
    }

    /**
     * @return string The URL requested by the user (relative, no host included).
     */
    public function getUrlRequested() {
        return $this->urlRequested;
    }


    /**
     * @param $headerName string The header key, case-insensitive.
     * @return string The value of the said header or null if not present.
     */
    public function getHeader($headerName) {
        $headerName = strtolower($headerName);
        if (array_key_exists($headerName, $this->headers)) {
            return $this->headers[$headerName];
        }
        return null;
    }

    /**
     * @param $parameterName string The query string parameter name, case-SENSITIVE.
     * @return bool Whether the parameter is present.
     */
    public function hasQueryStringParameter($parameterName) {
        return (array_key_exists($parameterName, $this->queryStringParameters));
    }

    /**
     * @param $parameterName string The query string parameter name, case-SENSITIVE.
     * @return string The value of the said parameter or null if not present.
     */
    public function getQueryStringParameter($parameterName) {
        if (array_key_exists($parameterName, $this->queryStringParameters)) {
            return $this->queryStringParameters[$parameterName];
        }
        return null;
    }

    /**
     * @return array An associative array of ["parameterName" => "parameterValue"]
     */
    public function getQueryStringParameters() {
        return $this->queryStringParameters;
    }

    /**
     * NB: only applicable to POSTs.
     *
     * @param $parameterName string The form data parameter name, case-SENSITIVE.
     * @return bool Whether the parameter is present.
     */
    public function hasFormParameter($parameterName) {
        return (array_key_exists($parameterName, $this->formData));
    }

    /**
     * NB: only applicable to POSTs.
     *
     * @param $parameterName string The form data parameter name, case-SENSITIVE.
     * @return string The value of the said parameter or null if not present.
     */
    public function getFormParameter($parameterName) {
        if (array_key_exists($parameterName, $this->formData)) {
            return $this->formData[$parameterName];
        }
        return null;
    }

    /**
     * NB: only applicable to POSTs.
     *
     * @return array An associative array of ["parameterName" => "parameterValue"]
     */
    public function getFormParameters() {
        return $this->formData;
    }

    public function isAjax() {
        return $this->getHeader("X-Requested-With") !== null && strtolower($this->getHeader("X-Requested-With")) == "xmlhttprequest";
    }


}
