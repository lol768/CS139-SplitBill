<?php

namespace BillSplitter\Controller;

use BillSplitter\DependencyInjection\IContainer;
use BillSplitter\Helper\IControllerHelper;
use BillSplitter\Session\IFlashSession;

class DebugController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    /**
     * @var IContainer
     */
    private $container;

    public function __construct(IControllerHelper $helper, IContainer $container) {
        $this->h = $helper;
        $this->container = $container;
    }

    /**
     * GET /iocDebug.php
     */
    public function getContainerInfo() {
        return $this->h->getViewResponse("containerDebug", array(
            "bindings" => $this->container->getInterfaceBindings(),
            "singletons" => $this->container->getSingletonNames(),
        ));
    }

}
