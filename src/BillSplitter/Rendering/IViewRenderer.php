<?php

namespace BillSplitter\Rendering;

interface IViewRenderer {

    public function renderView($name, array $variables);

}
