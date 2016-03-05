<?php

namespace BillSplitter\Controller;

abstract class AbstractController {

    /**
     * @param string $action The lowercased action
     * @param string $method The lowercased request method
     * @return array Array of parameters, ["parameterName" => ["required" => true, "default" => "x"]]
     */
    public function getQueryParametersForAction($action, $method) {
        return array();
    }
}
