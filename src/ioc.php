<?php
use BillSplitter\DependencyInjection\IContainer;

/**
 * Sets up dependency mappings in our IoC container.
 *
 * @param IContainer $container The IoC container instance.
 */
function wireUpContainer(IContainer $container) {
    $container->registerAbstractImplementation("\\BillSplitter\\Rendering\\IViewRenderer", "\\BillSplitter\\Rendering\\ViewRenderer");
    $container->registerAbstractImplementation("\\BillSplitter\\Helper\\IControllerHelper", "\\BillSplitter\\Helper\\ControllerHelper");
    $container->registerAbstractImplementation("\\BillSplitter\\IApplication", "\\BillSplitter\\Application");
    $container->registerAbstractImplementation("\\BillSplitter\\Session\\IUserSession", "\\BillSplitter\\Session\\UserSession");
    $container->registerAbstractImplementation("\\BillSplitter\\Session\\IFlashSession", "\\BillSplitter\\Session\\FlashSession");
    $container->registerAbstractImplementation("\\BillSplitter\\Filter\\IFilterConfiguration", "\\BillSplitter\\Filter\\FilterConfiguration");
    $container->registerAbstractImplementation("\\BillSplitter\\Helper\\IRequestHelper", "\\BillSplitter\\Helper\\RequestHelper");
    $container->registerAbstractImplementation("\\BillSplitter\\Security\\IAntiRequestForgery", "\\BillSplitter\\Security\\AntiRequestForgeryManager");
    $container->registerAbstractImplementation("\\BillSplitter\\ItsAuthentication\\IOAuthManager", "\\BillSplitter\\ItsAuthentication\\PsOAuthManager");

    $container->registerSingleton(getFilterConfiguration($container));
    $container->registerSingleton($container->resolveClassInstance("\\BillSplitter\\Session\\FlashSession"));
    $container->registerSingleton($container->resolveClassInstance("\\BillSplitter\\Database\\SqliteDatabaseManager"));
    $container->registerSingleton($container->resolveClassInstance("\\BillSplitter\\Helper\\IRequestHelper")->getCurrentRequestInstance());
}
