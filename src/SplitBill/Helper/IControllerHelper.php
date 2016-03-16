<?php

namespace SplitBill\Helper;

interface IControllerHelper {

    public function getViewResponse($viewName, $vars, $statusCode=200);
    public function getPrettyErrorResponse($error);
    public function getJsonResponse($data, $statusCode=200);
    public function setActiveNavigationItem($linkText);
    public function requireLoggedIn();

}
