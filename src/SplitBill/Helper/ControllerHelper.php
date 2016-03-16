<?php

namespace SplitBill\Helper;

use SplitBill\Frontend\NavigationCollection;
use SplitBill\Rendering\IViewRenderer;
use SplitBill\Response\JsonResponse;
use SplitBill\Response\ViewResponse;

class ControllerHelper implements IControllerHelper {

    /**
     * @var IViewRenderer
     */
    private $viewRenderer;
    /**
     * @var NavigationCollection
     */
    private $nav;

    /**
     * ControllerHelper constructor.
     * @param IViewRenderer $viewRenderer
     * @param NavigationCollection $nav
     */
    public function __construct(IViewRenderer $viewRenderer, NavigationCollection $nav) {
        $this->viewRenderer = $viewRenderer;
        $this->nav = $nav;
    }

    public function getViewResponse($viewName, $vars, $statusCode = 200) {
        $vars['leftNav'] = $this->nav->getLeftCollection();
        $vars['rightNav'] = $this->nav->getRightCollection();

        $resp = new ViewResponse($viewName, $statusCode);
        $body = $this->viewRenderer->renderView($viewName, $vars);
        $resp->setResponseBody($body);
        return $resp;
    }

    public function getJsonResponse($data, $statusCode = 200) {
        return new JsonResponse($data, $statusCode);
    }

    public function setActiveNavigationItem($linkText) {
        $item = $this->nav->getItemByName($linkText);
        if ($item === null) {
            throw new \InvalidArgumentException("No such navigation item.");
        }
        $this->nav->setAllInactive();
        $item->setActive(true);
    }

    public function getPrettyErrorResponse($error) {
        return $this->getViewResponse("genericErrors", array("title" => "Request error", "errors" => array($error)), 400);
    }
}
