<?php
use SplitBill\Application;
use SplitBill\DependencyInjection\Container;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Exception\AutoloaderException;
use SplitBill\Filter\FilterConfiguration;
use SplitBill\Session\FlashSession;

/**
 * Namespace-aware autoloader.
 *
 * @param string $class Full class name.
 * @throws AutoloaderException If we can't load the class.
 */
function namespacedDirectoryLoader($class) {
    $pathOnFilesystem = dirname(__FILE__) . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
    if (file_exists($pathOnFilesystem) !== true) {
        throw new AutoloaderException("Couldn't autoload class $class, tried path $pathOnFilesystem");
    }
    require_once($pathOnFilesystem);
}

/**
 * Helper function used to include a view with minimal scope contamination.
 *
 * @param string $_nameOfView The view name.
 * @param array $_variables Associative array of variables to make available to our view.
 */
function isolatedViewInclude($_nameOfView, $_variables) {
    extract($_variables);
    unset($_variables);
    require($_nameOfView);
}

/**
 * @return FilterConfiguration The configuration instance of pre and post request/response filters.
 */
function getFilterConfiguration(IContainer $container) {
    $fc = new FilterConfiguration();
    $fc->addPostFilter($container->resolveClassInstance("\\SplitBill\\Filter\\Post\\ContentSecurityPolicy"));
    $fc->addPostFilter($container->resolveClassInstance("\\SplitBill\\Filter\\Post\\XFrameOptions"));
    $fc->addPreFilter($container->resolveClassInstance("\\SplitBill\\Filter\\Pre\\AntiRequestForgeryFilter"));
    return $fc;
}

/**
 * Converts errors to exceptions so we can report them.
 */
function errorsToExceptions($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

/**
 * Displays exceptions and a stack trace when things go wrong.
 *
 * @param Exception $e The exception to display.
 */
function simpleExceptionHandler($e) {
    header("HTTP/1.1 500 Internal Server Error");
    $error = "";

    try {
        /** @var \SplitBill\Handler\IExceptionHandlerManager $handler */
        $container = Application::getInstance()->getContainer();
        $handler = $container->resolveClassInstance("\\SplitBill\\Handler\\IExceptionHandlerManager");
        /** @var \SplitBill\Request\HttpRequest $request */
        $request = $container->resolveClassInstance("\\SplitBill\\Request\\HttpRequest");
        $response = $handler->handleExceptionUsingRegisteredHandlers($e, $request);
        if ($response !== null) {
            foreach ($response->getAllHeaders() as $headerName => $headerValue) {
                header("${headerName}: $headerValue");
            }
            die($response->getResponseBody());
        }
    } catch (\Exception $e) {
        $error .= "(Additionally, a " . get_class($e) . " was thrown whilst trying to handle this exception)\n\n";
        // if this fails, fine - we'll just fall back to the usual handler below
    }


    header("Content-Type: text/plain");
    if ($e instanceof ParseError) {
        $error .= "    __  ___          _           \n   / / |__ \\        | |          \n  / /     ) |  _ __ | |__  _ __  \n < <     / /  | '_ \\| '_ \\| '_ \\ \n  \\ \\   |_|   | |_) | | | | |_) |\n   \\_\\  (_)   | .__/|_| |_| .__/ \n              | |         | |    \n              |_|         |_|    \n\n";
        $error .= "A parse error was encountered whilst interpreting the source code in file:\n\n" . $e->getFile() . " on line " . $e->getLine() . "\n\n";
        $contents = file($e->getFile());
        $error .= "The error was: " . $e->getMessage();
        $error .= "\n\nRelevant code snippet:";
        if ($e->getLine() != 1) {
            $error .= "\n\n  " . ltrim($contents[$e->getLine()-2]);
        }
        $error .= "> " . ltrim($contents[$e->getLine()-1]);
        if ($e->getLine() != count($contents)) {
            $error .= "  " . ltrim($contents[$e->getLine()]);
        }

    } else {
        $error .= "     __  \n _ .' _| \n(_)| |   \n _ | |   \n(_)| |_  \n   `.__| \n        \n\n\n";
        $error .= "An exception was thrown whilst rendering this page:\n\n" . get_class($e) . ": " . $e->getMessage() . "\n\n";
        $error .= $e->getTraceAsString();
    }

    die($error);
}

function shutdownHandler() {
    $e = error_get_last();
    if ($e['type'] === E_ERROR) {
        header("HTTP/1.1 500 Internal Server Error");
        header("Content-Type: text/plain");
        echo "  ___   _   ___    ______    _        _                            \n |  _| | | |_  |  |  ____|  | |      | |                           \n | |   | |   | |  | |__ __ _| |_ __ _| |   ___ _ __ _ __ ___  _ __ \n | |   | |   | |  |  __/ _` | __/ _` | |  / _ \ '__| '__/ _ \| '__|\n | |   |_|   | |  | | | (_| | || (_| | | |  __/ |  | | | (_) | |   \n | |_  (_)  _| |  |_|  \__,_|\__\__,_|_|  \___|_|  |_|  \___/|_|   \n |___|     |___|                                                   \n                                                                   ";
        echo "\n" . $e['message'] . " in file " . $e['file'];
        echo "\n\nNo further information is available.";
    }
}


/**
 * Entry point of all requests.
 * This is where we set everything up.
 */
function startApp() {
    spl_autoload_register("namespacedDirectoryLoader"); // lets us autoload classes
    set_error_handler("errorsToExceptions"); // converts errors to exceptions so we can see them
    date_default_timezone_set("UTC"); // standardise datetime
    set_exception_handler("simpleExceptionHandler"); // print out a pretty text page when things go wrong
    register_shutdown_function("shutdownHandler");

    $container = new Container(); // our IoC container
    require_once("ioc.php");
    wireUpContainer($container); // set up dependency mappings

    $app = new Application($container); // singleton application, one per request
    Application::setInstance($app); // allow us to retrieve the application instance from anywhere
    $container->registerSingleton($app);
}

/**
 * Helper function to be called by frontend pages to pass off control to controller actions.
 *
 * @param string $controller The name of the controller which will be handling the request.
 * @param string $action The name of the controller action.
 */
function handleResponseForPage($controller, $action) {
    require_once("view_helpers.php");

    $app = Application::getInstance();
    $container = $app->getContainer();
    /** @var \SplitBill\Handler\ControllerRoutingHandler $handler */
    $handler = $container->resolveClassInstance("\\SplitBill\\Handler\\ControllerRoutingHandler");
    $handler->handleRequest($controller, $action);
    die();
}

startApp();
