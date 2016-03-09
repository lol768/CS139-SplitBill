<?php

namespace SplitBill\Rendering;

interface IViewRenderer {

    public function renderView($name, array $variables);

}
