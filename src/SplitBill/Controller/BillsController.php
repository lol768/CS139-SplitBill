<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Email\IEmailService;
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
use SplitBill\Request\HttpRequest;
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
    /**
     * @var IEmailService
     */
    private $emailService;
    /**
     * @var HttpRequest
     */
    private $req;


    public function __construct(IControllerHelper $helper, IUserRepository $userRepo,
                                IBillRepository $billRepo, IGroupRepository $groupRepo,
                                IAuthenticationManager $authMan, IPaymentRepository $paymentRepo,
                                IEmailService $emailService, HttpRequest $req) {
        $this->h = $helper;
        $this->h->requireLoggedIn();
        $this->h->setActiveNavigationItem("Bills");
        $this->groupRepo = $groupRepo;
        $this->authMan = $authMan;
        $this->billRepo = $billRepo;
        $this->userRepo = $userRepo;
        $this->paymentRepo = $paymentRepo;
        $this->emailService = $emailService;
        $this->req = $req;
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
            $totalDue += $payment->getAmount();
            $duePaymentsOut[] = $this->getAdditionalDataForPayment($payment);
        }

        $completedPayments = $this->paymentRepo->getCompletedPaymentsForUserId($user->getUserId());
        $completedPaymentsOut = array();
        foreach ($completedPayments as $payment) {
            $completedPaymentsOut[] = $this->getAdditionalDataForPayment($payment);
        }
        return $this->h->getViewResponse("billDashboard", array("title" => "Bills", "completedPayments" => $completedPaymentsOut, "duePayments" => $duePaymentsOut, "billableGroups" => $billableGroups, "totalDue" => $totalDue));
    }

    /**
     * POST /add_bill.php
     * @param BillCreationFormRequest $formRequest
     * @return AbstractResponse
     */
    public function postAddBill(BillCreationFormRequest $formRequest, IFlashSession $flash) {
        if (!$formRequest->isValid()) {
            return new RedirectResponse("bills.php");
        }

        $bill = new Bill($this->authMan->getEffectiveUser()->getUserId(),
            $formRequest->getGroup()->getGroupId(), $formRequest->getAmount(),
            $formRequest->getDescription(), $formRequest->getCompany());

        $this->billRepo->add($bill);
        $this->createPaymentsFromBill($bill, $formRequest->getGroup());
        $flash->set("wsb", array($this->authMan->getEffectiveUser()->getFirstName() . " has added a new bill to group " . $formRequest->getGroup()->getName()));
        return new RedirectResponse("bills.php");
    }

    private function createPaymentsFromBill(Bill $bill, Group $group) {
        $applicableRelations = $this->groupRepo->getRelationsForGroup($group);
        $count = count($applicableRelations);
        $amountPayable = round(($bill->getAmount() / (float)$count));
        foreach ($applicableRelations as $relation) {
            $payment = new Payment($bill->getBillId(), $relation->getUser()->getUserId(), false, $amountPayable);
            $this->paymentRepo->add($payment);
            $emailVars = array(
                "name" => $relation->getUser()->getName(),
                "billsUrl" => $this->getBillsUrl()
            );
            $this->emailService->sendEmail($relation->getUser()->getEmail(), "Payment due", "paymentDue", $emailVars);
        }
    }

    public function postMarkPaid(HttpRequest $req, IAuthenticationManager $authMan, IFlashSession $flash) {
        $duePayments = $this->paymentRepo->getPendingPaymentsForUserId($authMan->getEffectiveUser()->getUserId());
        $i = 0;
        foreach ($duePayments as $duePayment) {
            if ($req->hasFormParameter("check-" . $duePayment->getPaymentId())) {
                $duePayment->setCompleted(true);
                $i++;
                $this->paymentRepo->update($duePayment);
            }
        }
        if ($i > 0) {
            $flash->set("wsb", array($this->authMan->getEffectiveUser()->getFirstName() . " has paid $i bills!"));
            $flash->set("flash", array("message" => "Bills marked as paid!", "type" => "success"));
        }

        return new RedirectResponse("bills.php");
    }

    /**
     * @param Payment $payment
     * @return array
     */
    private function getAdditionalDataForPayment(Payment $payment) {
        $bill = $this->billRepo->getByBillId($payment->getBillId());
        $duePayment = array(
            "payment" => $payment,
            "bill" => $bill,
            "group" => $this->groupRepo->getById($bill->getGroupId())
        );
        return $duePayment;
    }

    private function getBillsUrl() {
        $currentUrl = "http://" . $this->req->getHeader("Host") . $this->req->getUrlRequested();
        $url = preg_replace("/[A-Za-z_]+\\.php.*$/", "bills.php", $currentUrl);
        return $url;
    }

}
