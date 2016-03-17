<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\IApplication;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;
use SplitBill\Validation\MasqueradeFormRequest;

class DebugController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;

    /**
     * @var IContainer
     */
    private $container;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;

    public function __construct(IControllerHelper $helper, IContainer $container, IAuthenticationManager $authMan) {
        $this->h = $helper;
        $this->container = $container;
        $this->authMan = $authMan;
    }

    public function getQueryParametersForAction($action, $method) {
        return parent::getQueryParametersForAction($action, $method); // TODO: Change the autogenerated stub
    }


    /**
     * GET /ioc_debug.php
     */
    public function getContainerInfo() {
        return $this->h->getViewResponse("containerDebug", array(
            "bindings" => $this->container->getInterfaceBindings(),
            "singletons" => $this->container->getSingletonNames(),
        ));
    }

    public function getMasquerade() {
        $this->authMan->unmasquerade();
        return new RedirectResponse("index.php");
    }

    public function postMasquerade(HttpRequest $req, IApplication $app, MasqueradeFormRequest $m, IFlashSession $flash) {
        if (in_array($req->getIpAddress(), $app->getConfig()['masquerade_ips'])) {
            if ($m->getUid() != $this->authMan->getRealUser()->getUserId()) {
                $this->authMan->masquerade($m->getUid());
            }
        } else {
            $flash->set("flash", array("message" => "You're not authorised to do that.", "type" => "error"));
        }
        return new RedirectResponse("index.php");
    }

}
