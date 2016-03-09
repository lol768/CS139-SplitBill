<?php

namespace SplitBill\Filter\Pre;

use SplitBill\Exception\CsrfException;
use SplitBill\Request\HttpRequest;
use SplitBill\Security\IAntiRequestForgery;
use SplitBill\Security\SecurityUtil;

class AntiRequestForgeryFilter implements IPreResponseFilter {
    /**
     * @var IAntiRequestForgery
     */
    private $antiRequestForgery;

    /**
     * AntiRequestForgeryFilter constructor.
     * @param IAntiRequestForgery $antiRequestForgery Anti request forgery instance.
     */
    public function __construct(IAntiRequestForgery $antiRequestForgery) {
        $this->antiRequestForgery = $antiRequestForgery;
    }

    /**
     * @param HttpRequest $request
     * @return \SplitBill\Response\AbstractResponse|void
     * @throws CsrfException
     */
    public function handleRequest(HttpRequest $request) {
        if ($request->getRequestMethod() === "POST") {
            if (!$this->antiRequestForgery->hasCsrfTokenSet()) {
                throw new CsrfException("No CSRf token present in session for a POST request. Refresh homepage and try again.");
            }

            if (!$request->hasFormParameter("__csrf_token")) {
                throw new CsrfException("No CSRf token present on a POST request.");
            }

            if (!SecurityUtil::timingSafeComparison($this->antiRequestForgery->getCurrentCsrfToken(), $request->getFormParameter("__csrf_token"))) {
                throw new CsrfException("Mismatching CSRF token.");
            }
        }

        if (!$this->antiRequestForgery->hasCsrfTokenSet()) {
            $this->antiRequestForgery->createCsrfTokenForUser();
        }
    }
}
