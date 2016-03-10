<?php
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Rendering\DataProvider\BrandViewDataProvider;
use SplitBill\Rendering\DataProvider\ViewDataProviderManager;

function provideViewDataProviderManager(IContainer $container) {
    $manager = new ViewDataProviderManager();
    $manager->registerProvider(new BrandViewDataProvider());
    $manager->registerProvider($container->resolveClassInstance("\\SplitBill\\Rendering\\DataProvider\\AuthViewDataProvider"));
    $manager->registerProvider($container->resolveClassInstance("\\SplitBill\\Rendering\\DataProvider\\FormErrorsViewDataProvider"));
    return $manager;
}

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
    $container->registerAbstractImplementation("\\SplitBill\\Rendering\\DataProvider\\IViewDataProviderManager", "\\SplitBill\\Rendering\\DataProvider\\ViewDataProviderManager");
    $container->registerAbstractImplementation("\\SplitBill\\Authentication\\IAuthenticationManager", "\\SplitBill\\Authentication\\SessionAuthenticationManager");
    $container->registerAbstractImplementation("\\SplitBill\\Repository\\IUserRepository", "\\SplitBill\\Repository\\SqliteUserRepository");
    $container->registerAbstractImplementation("\\SplitBill\\Email\\IEmailService", "\\SplitBill\\Email\\MultipartEmailService");
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Session\\FlashSession"));

    $container->registerSingleton(getFilterConfiguration($container));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Database\\SqliteDatabaseManager"));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Helper\\IRequestHelper")->getCurrentRequestInstance());
    $container->registerSingleton(provideViewDataProviderManager($container));

}
