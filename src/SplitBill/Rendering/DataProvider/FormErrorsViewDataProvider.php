<?php

namespace SplitBill\Rendering\DataProvider;


use SplitBill\Session\IFlashSession;

class FormErrorsViewDataProvider implements IViewDataProvider {
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
        if ($this->flash->has("errors")) {
            $vars['errors'] = $this->flash->get("erorrs");
            $vars['oldData'] = $this->flash->get("oldData");
        }
    }
}
