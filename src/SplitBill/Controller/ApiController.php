<?php

namespace SplitBill\Controller;

use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IUserRepository;
use SplitBill\Response\JsonResponse;
use SplitBill\Session\IFlashSession;

class ApiController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IUserRepository
     */
    private $userRepo;


    public function __construct(IControllerHelper $helper, IUserRepository $userRepo) {
        $this->h = $helper;
        $this->userRepo = $userRepo;
    }

    public function getQueryParametersForAction($action, $method) {
        if ($action === "userssearch") {
            return array(
                "q" => array("required" => true)
            );
        }
        return parent::getQueryParametersForAction($action, $method);
    }


    /**
     * GET /ioc_debug.php
     */
    public function getUsersSearch($q) {
        $items = $this->userRepo->getFuzzyMatches($q);
        $response = array("data" => array());
        foreach($items as $item) {
            $response['data'][] = array("name" => $item->getName(), "uid" => $item->getUserId(), "avatar" => $item->getHasAvatar());
        }
        return new JsonResponse($response);
    }

}
