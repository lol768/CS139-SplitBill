<?php

namespace SplitBill\Rendering;

use SplitBill\Exception\ViewRenderException;
use SplitBill\IApplication;
use SplitBill\Rendering\DataProvider\IViewDataProviderManager;

class ViewRenderer implements IViewRenderer {

    /**
     * @var IApplication The application.
     */
    private $app;

    /**
     * @var IViewDataProviderManager
     */
    private $providerManager;

    public function __construct(IApplication $app, IViewDataProviderManager $providerManager) {
        $this->app = $app;
        $this->providerManager = $providerManager;
    }

    public function renderView($name, array $variables) {
        foreach ($this->providerManager->getAllProviders() as $provider) {
            $provider->modifyView($name, $variables);
        }

        $viewPath = $this->app->getRootPath() . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $name . ".php";
        if (!file_exists($viewPath)) {
            throw new ViewRenderException("The view at $viewPath doesn't exist");
        }
        ob_start();
        isolatedViewInclude($viewPath, $variables);
        return ob_get_clean();
    }


}
