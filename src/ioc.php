<?php
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Handler\ExceptionHandlerManager;
use SplitBill\Rendering\DataProvider\BrandViewDataProvider;
use SplitBill\Rendering\DataProvider\ViewDataProviderManager;

function provideViewDataProviderManager(IContainer $container) {
    $manager = new ViewDataProviderManager();
    $manager->registerProvider(new BrandViewDataProvider());
    $manager->registerProvider($container->resolveClassInstance("\\SplitBill\\Rendering\\DataProvider\\AuthViewDataProvider"));
    $manager->registerProvider($container->resolveClassInstance("\\SplitBill\\Rendering\\DataProvider\\FormErrorsViewDataProvider"));
    $manager->registerProvider($container->resolveClassInstance("\\SplitBill\\Rendering\\DataProvider\\FlashMessageViewDataProvider"));
    $manager->registerProvider($container->resolveClassInstance("\\SplitBill\\Rendering\\DataProvider\\ProfilingDataProvider"));

    return $manager;
}

function provideExceptionHandlerManager(IContainer $container) {
    $manager = new ExceptionHandlerManager();
    $manager->registerExceptionHandler($container->resolveClassInstance("\\SplitBill\\Exception\\Handler\\LoginRedirectHandler"));
    $manager->registerExceptionHandler($container->resolveClassInstance("\\SplitBill\\Exception\\Handler\\NotImplementedHandler"));
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
    $container->registerAbstractImplementation("\\SplitBill\\Repository\\IGroupRepository", "\\SplitBill\\Repository\\SqliteGroupRepository");
    $container->registerAbstractImplementation("\\SplitBill\\Repository\\IBillRepository", "\\SplitBill\\Repository\\SqliteBillRepository");
    $container->registerAbstractImplementation("\\SplitBill\\Repository\\IPaymentRepository", "\\SplitBill\\Repository\\SqlitePaymentRepository");
    $container->registerAbstractImplementation("\\SplitBill\\Database\\IEntityMapper", "\\SplitBill\\Database\\SqliteEntityMapper");

    $container->registerAbstractImplementation("\\SplitBill\\Handler\\IExceptionHandlerManager", "\\SplitBill\\Handler\\ExceptionHandlerManager");
    /** Email binding */
    //$container->registerAbstractImplementation("\\SplitBill\\Email\\IEmailService", "\\SplitBill\\Email\\DummyEmailService");
    $container->registerAbstractImplementation("\\SplitBill\\Email\\IEmailService", "\\SplitBill\\Email\\MultipartEmailService");

    $container->registerAbstractImplementation("\\SplitBill\\Repository\\IEmailConfirmationRepository", "\\SplitBill\\Repository\\SqliteEmailConfirmationRepository");
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Session\\FlashSession"));

    $container->registerSingleton(getFilterConfiguration($container));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Database\\SqliteDatabaseManager"));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Helper\\IRequestHelper")->getCurrentRequestInstance());

    $container->registerSingleton(provideViewDataProviderManager($container));
    $container->registerSingleton(provideExceptionHandlerManager($container));
    $container->registerSingleton($container->resolveClassInstance("\\SplitBill\\Authentication\\IAuthenticationManager"));


}
