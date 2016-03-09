<?php
use SplitBill\DependencyInjection\IContainer;

/**
 * Sets up dependency mappings in our IoC container.
 *
 * @param IContainer $container The IoC container instance.
 */
function wireUpContainer(IContainer $container) {
    $container->registerAbstractImplementation("\\SplitBill\\Rendering\\IViewRenderer", "\\SplitBill\\Rendering\\ViewRenderer");
    $container->registerAbstractImplementation("\\SplitBill\\Helper\\IControllerHelper", "\\SplitBill\\Helper\\ControllerHelper");
    $container->registerAbstractImplementation("\\SplitBill\\IApplication", "\\SplitBill\\Application");
    $container->registerAbstractImplementation("\\SplitBill\\Session\\IUserSession", "\\SplitBill\\Session\\UserSession");
    $container->registerAbstractImplementation("\\SplitBill\\Session\\IFlashSession", "\\SplitBill\\Session\\FlashSession");
    $container->registerAbstractImplementation("\\SplitBill\\Filter\\IFilterConfiguration", "\\SplitBill\\Filter\\FilterConfiguration");
    $container->registerAbstractImplementation("\\SplitBill\\Helper\\IRequestHelper", "\\SplitBill\\Helper\\RequestHelper");
    $container->registerAbstractImplementation("\\SplitBill\\Security\\IAntiRequestForgery", "\\SplitBill\\Security\\AntiRequestForgeryManager");
    $container->registerAbstractImplementation("\\SplitBill\\ItsAuthentication\\IOAuthManager", "\\SplitBill\\ItsAuthentication\\PsOAuthManager");

    $container->registerSingleton(getFilterConfiguration($container));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Session\\FlashSession"));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Database\\SqliteDatabaseManager"));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Helper\\IRequestHelper")->getCurrentRequestInstance());
}
