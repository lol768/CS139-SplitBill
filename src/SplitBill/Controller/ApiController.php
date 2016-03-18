<?php

namespace SplitBill\Controller;

use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IBillRepository;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Repository\IPaymentRepository;
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
    /**
     * @var IBillRepository
     */
    private $billRepo;
    /**
     * @var IGroupRepository
     */
    private $groupRepo;
    /**
     * @var IPaymentRepository
     */
    private $paymentRepo;


    public function __construct(IControllerHelper $helper, IUserRepository $userRepo, IBillRepository $billRepo, IGroupRepository $group, IPaymentRepository $paymentRepo) {
        $this->h = $helper;
        $this->userRepo = $userRepo;
        $this->billRepo = $billRepo;
        $this->groupRepo = $group;
        $this->paymentRepo = $paymentRepo;
    }

    public function getQueryParametersForAction($action, $method) {
        if ($action === "userssearch") {
            return array(
                "q" => array("required" => true)
            );
        } else if ($action === "bill") {
            return array(
                "id" => array("required" => true)
            );
        }
        return parent::getQueryParametersForAction($action, $method);
    }

    public function getBill($id) {
        $bill = $this->billRepo->getByBillId($id);
        $group = $this->groupRepo->getById($bill->getGroupId());
        $payments = $this->paymentRepo->getPaymentsForBill($bill->getBillId());
        $viewPayments = array();
        foreach ($payments as $payment) {
            $viewPayments[] = array("payment" => $payment, "user" => $this->userRepo->getById($payment->getUserId()));
        }
        return $this->h->getViewResponse("billDetails", array("payments" => $viewPayments, "bill" => $bill, "group" => $group));
    }


    public function getUsersSearch($q) {
        $items = $this->userRepo->getFuzzyMatches($q);
        $response = array("data" => array());
        foreach($items as $item) {
            $response['data'][] = array("name" => $item->getName(), "uid" => $item->getUserId(), "avatar" => $item->getHasAvatar());
        }
        return new JsonResponse($response);
    }

}
