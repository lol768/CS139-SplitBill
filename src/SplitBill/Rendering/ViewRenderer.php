<?php

namespace SplitBill\Rendering;

use SplitBill\Exception\ViewRenderException;
use SplitBill\IApplication;

class ViewRenderer implements IViewRenderer {

    /**
     * @var IApplication The application.
     */
    private $app;

    public function __construct(IApplication $app) {
        $this->app = $app;
    }

    public function renderView($name, array $variables) {
        $viewPath = $this->app->getRootPath() . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $name . ".php";
        if (!file_exists($viewPath)) {
            throw new ViewRenderException("The view at $viewPath doesn't exist");
        }
        ob_start();
        isolatedViewInclude($viewPath, $variables);
        return ob_get_clean();
    }


}
