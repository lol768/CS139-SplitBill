<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Entity\Bill;
use SplitBill\Entity\Group;
use SplitBill\Entity\Payment;
use SplitBill\Enum\GroupRelationType;
use SplitBill\Exception\NotImplementedException;
use SplitBill\Helper\IControllerHelper;
use SplitBill\Repository\IBillRepository;
use SplitBill\Repository\IGroupRepository;
use SplitBill\Repository\IPaymentRepository;
use SplitBill\Repository\IUserRepository;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\JsonResponse;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;
use SplitBill\Validation\BillCreationFormRequest;

class BillsController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IGroupRepository
     */
    private $groupRepo;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;
    /**
     * @var IBillRepository
     */
    private $billRepo;
    /**
     * @var IUserRepository
     */
    private $userRepo;
    /**
     * @var IPaymentRepository
     */
    private $paymentRepo;


    public function __construct(IControllerHelper $helper, IUserRepository $userRepo,
                                IBillRepository $billRepo, IGroupRepository $groupRepo,
                                IAuthenticationManager $authMan, IPaymentRepository $paymentRepo) {
        $this->h = $helper;
        $this->h->requireLoggedIn();
        $this->h->setActiveNavigationItem("Bills");
        $this->groupRepo = $groupRepo;
        $this->authMan = $authMan;
        $this->billRepo = $billRepo;
        $this->userRepo = $userRepo;
        $this->paymentRepo = $paymentRepo;
    }

    /**
     * GET /bills.php
     */
    public function getDashboard() {
        $user = $this->authMan->getEffectiveUser();
        $billableGroups = array_merge($this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), GroupRelationType::ADMIN), $this->groupRepo->getGroupsSatisfyingRelation($user->getUserId(), GroupRelationType::OWNER));
        $duePayments = $this->paymentRepo->getPendingPaymentsForUserId($user->getUserId());
        $duePaymentsOut = array();
        $totalDue = 0;
        foreach ($duePayments as $payment) {
            $bill = $this->billRepo->getByBillId($payment->getBillId());
            $totalDue += $payment->getAmount();
            $duePaymentsOut[] = array("payment" => $payment, "bill" => $bill, "group" => $this->groupRepo->getById($bill->getGroupId()));
        }
        return $this->h->getViewResponse("billDashboard", array("title" => "Bills", "duePayments" => $duePaymentsOut, "billableGroups" => $billableGroups, "totalDue" => $totalDue));
    }

    /**
     * POST /add_bill.php
     * @param BillCreationFormRequest $formRequest
     * @return AbstractResponse
     */
    public function postAddBill(BillCreationFormRequest $formRequest) {
        if (!$formRequest->isValid()) {
            return new RedirectResponse("bills.php");
        }

        $bill = new Bill($this->authMan->getEffectiveUser()->getUserId(),
            $formRequest->getGroup()->getGroupId(), $formRequest->getAmount(),
            $formRequest->getDescription(), $formRequest->getCompany());

        $this->billRepo->add($bill);
        $this->createPaymentsFromBill($bill, $formRequest->getGroup());
        return new RedirectResponse("bills.php");
    }

    private function createPaymentsFromBill(Bill $bill, Group $group) {
        $applicableRelations = $this->groupRepo->getRelationsForGroup($group);
        $count = count($applicableRelations);
        $amountPayable = round(($bill->getAmount() / (float)$count));
        foreach ($applicableRelations as $relation) {
            $payment = new Payment($bill->getBillId(), $relation->getUser()->getUserId(), false, $amountPayable);
            $this->paymentRepo->add($payment);
        }
    }

}
