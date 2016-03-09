<?php

namespace SplitBill\Rendering\DataProvider;

class BrandViewDataProvider implements IViewDataProvider {

    public function modifyView($viewName, &$vars) {
        $vars["brand"] = "SplitBill";
    }
}
