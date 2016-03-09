<?php

namespace SplitBill\Rendering\DataProvider;

interface IViewDataProvider {

    public function modifyView($viewName, &$vars);

}
