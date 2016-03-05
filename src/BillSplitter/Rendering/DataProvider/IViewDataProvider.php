<?php

namespace BillSplitter\Rendering\DataProvider;

interface IViewDataProvider {

    public function modifyView($viewName, &$vars);

}
