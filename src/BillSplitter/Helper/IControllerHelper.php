<?php

namespace BillSplitter\Helper;

interface IControllerHelper {

    public function getViewResponse($viewName, $vars, $statusCode=200);
    public function getJsonResponse($data, $statusCode=200);
    public function setActiveNavigationItem($linkText);

}
