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
            $vars['flashMessage'] = $this->flash->get("flash")['message'];
            $vars['flashType'] = $this->flash->get("flash")['type'];
        }
    }
}
