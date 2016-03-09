<?php

namespace SplitBill\Helper;

use SplitBill\Request\HttpRequest;

class RequestHelper implements IRequestHelper {

    /**
     * Gets an instance of the current HttpRequest based on the context of the current request.
     * Don't call this from the cli SAPI.
     *
     * @return HttpRequest Object for the current request instance.
     */
    public function getCurrentRequestInstance() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $host = $_SERVER['HTTP_HOST'];
        $url = $_SERVER['REQUEST_URI'];

        $req = new HttpRequest($requestMethod, $this->getRequestHeaders(), $url, $_GET, $_POST, $host);
        return $req;
    }

    /**
     * Helper method to get all the request headers in a way that works on PHP 5.3 w/ nginx & apache.
     */
    private function getRequestHeaders() {
        $headers = array();

        if (function_exists("getallheaders")) {
            // we're either on Apache *or* we're on nginx/other FastCGI webservers w/ PHP >= 5.4
            // if this is the case then our lives are easy - just take the existing array and make all the keys lowercase
            foreach (getallheaders() as $headerName => $value) {
                $headers[strtolower($headerName)] = $value;
            }
            return $headers;
        } else {
            // otherwise, we need to do it ourselves..
            foreach (array_filter($_SERVER, function ($item) {
                return substr($item, 0, 5) === "HTTP_";
            }) as $item => $value) {
                $component = substr($item, 5);
                $component = strtolower(str_replace("_", "-", $component));
                $headers[$component] = $component;
            }
        }
        return $headers;
    }
}
