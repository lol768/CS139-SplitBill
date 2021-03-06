<?php

namespace SplitBill\Rendering\DataProvider;


use SplitBill\Session\IFlashSession;

class FlashMessageViewDataProvider implements IViewDataProvider {
    /**
     * @var IFlashSession
     */
    private $flash;

    /**
     * FormErrorsDataProvider constructor.
     * @param IFlashSession $flash
     */
    public function __construct(IFlashSession $flash) {
        $this->flash = $flash;
    }

    public function modifyView($viewName, &$vars) {
        if ($this->flash->has("flash")) {
            $flashResult = $this->flash->get("flash");
            $vars['flashMessage'] = $flashResult['message'];
            $vars['flashType'] = $flashResult['type'];
        }
        $vars['wsb'] = array();
        if ($this->flash->has("wsb")) {
            $vars['wsb'] = $this->flash->get("wsb");
        }
    }
}
