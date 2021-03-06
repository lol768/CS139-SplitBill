<?php

namespace SplitBill\Handler;

use ReflectionClass;
use ReflectionMethod;
use SplitBill\Controller\AbstractController;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Exception\BadRequestException;
use SplitBill\Exception\RequestHandlingException;
use SplitBill\Filter\IFilterConfiguration;
use SplitBill\Rendering\DataProvider\IViewDataProviderManager;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\AbstractResponse;
use SplitBill\Response\ViewResponse;
use SplitBill\Session\IFlashSession;
use SplitBill\Utilities\PhpCompatibility;
use SplitBill\Validation\IFormRequest;

class ControllerRoutingHandler {

    /**
     * @var string The request method (e.g. GET)
     */
    private $reqMethod;

    /**
     * @var string The (lowercase) action name.
     */
    private $action;

    /**
     * @var IContainer The IoC container.
     */
    private $container;
    /**
     * @var IFilterConfiguration
     */
    private $filterConfig;
    /**
     * @var HttpRequest
     */
    private $currentRequest;
    /**
     * @var IFlashSession
     */
    private $flash;

    /**
     * @param IContainer $container
     * @param IFilterConfiguration $filterConfig
     * @param HttpRequest $currentRequest
     */
    public function __construct(IContainer $container, IFilterConfiguration $filterConfig, HttpRequest $currentRequest, IFlashSession $flash) {
        $this->container = $container;
        $this->filterConfig = $filterConfig;
        $this->currentRequest = $currentRequest;
        $this->flash = $flash;
    }

    /**
     * Apply the pre-response filters to the request.
     *
     * @return AbstractResponse
     */
    public function handlePreFilters() {
        foreach ($this->filterConfig->getPreFilters() as $filter) {
            $out = $filter->handleRequest($this->currentRequest);
            if ($out instanceof AbstractResponse) {
                return $out; // fail fast here, we want nothing else to run.
            }
        }
        return null;
    }

    /**
     * Entry point for handling a controller-action request.
     *
     * @param string $controller Name of the controller.
     * @param string $action Name of the action on the above controller.
     * @throws RequestHandlingException
     */
    public function handleRequest($controller, $action) {
        $response = $this->handlePreFilters();
        if ($response === null) {
            $this->reqMethod = $this->currentRequest->getRequestMethod();
            $this->action = strtolower($action);

            $method = strtolower($this->reqMethod) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
            $controller = $this->getFullControllerName($controller);
            $controllerInstance = $this->container->resolveClassInstance($controller);

            if (!$controllerInstance instanceof AbstractController) {
                throw new RequestHandlingException("Instance of name $controller is not of type AbstractController");
            }

            $rc = new \ReflectionClass($controller);
            /** @var ViewResponse $response */
            $actionMethod = $rc->getMethod($method);
            $response = $this->transformResponseViaFilters($this->getResponseByInvokingAction($actionMethod, $controllerInstance));
        }
        header("HTTP/1.1 {$response->getStatusCode()} {$this->getStatusTextForCode($response->getStatusCode())}");
        $this->setHeadersFromResponse($response);
        echo $response->getResponseBody();
    }

    /**
     * Apply the post-response filters over the top of the response.
     *
     * @param AbstractResponse $response Input response
     * @return AbstractResponse Transformed response
     */
    public function transformResponseViaFilters($response) {
        foreach ($this->filterConfig->getPostFilters() as $filter) {
            $response = $filter->handleResponse($response);
        }
        return $response;
    }

    /**
     * @param string $controller The short name of the controller.
     * @return string Full class name.
     */
    private function getFullControllerName($controller) {
        return "\\SplitBill\\Controller\\" . $controller;
    }

    /**
     * @param ReflectionMethod $actionMethod The method we're going to invoke on our controller.
     * @param AbstractController $controllerInstance
     * @return AbstractResponse
     * @throws BadRequestException
     */
    private function getResponseByInvokingAction(ReflectionMethod $actionMethod, $controllerInstance) {
        $actionParameters = $actionMethod->getParameters();
        $paramsToSupply = array();
        $queryStringParams = $controllerInstance->getQueryParametersForAction($this->action, strtolower($this->reqMethod));

        foreach ($actionParameters as $param) {
            if ($param->getClass() === null) {
                $paramName = $param->getName();
                if ($paramName === "formData" && $this->reqMethod === "POST") {
                    $formData = PhpCompatibility::arrayFilterKeys($this->currentRequest->getFormParameters(), function($key) {
                        return $key !== "__csrf_token";
                    });
                    $paramsToSupply[] = $formData;
                    continue;
                }

                $paramValue = $this->getQueryStringParameterValue($paramName, $queryStringParams);

                $paramsToSupply[] = $paramValue;

            } else {
                /** @var ReflectionClass $reflectionClass */
                $reflectionClass = $param->getClass();
                if ($this->reqMethod === "POST" && in_array("SplitBill\\Validation\\IFormRequest", $reflectionClass->getInterfaceNames())) {
                    /** @var IFormRequest $request */
                    $request = $this->container->resolveClassInstance($reflectionClass->getName());
                    $request->receiveFrom($this->currentRequest->getFormParameters());
                    if (!$request->isValid()) {
                        $this->flash->set("errors", $request->getErrors());
                        $this->flash->set("oldData", $this->currentRequest->getFormParameters());
                    }
                    $paramsToSupply[] = $request;
                } else {
                    $paramsToSupply[] = $this->container->resolveClassInstance($reflectionClass->getName());
                }

            }
        }
        $response = $actionMethod->invokeArgs($controllerInstance, $paramsToSupply);
        return $response;
    }

    /**
     * Sends the response headers to the client.
     *
     * @param AbstractResponse $response The response object.
     */
    private function setHeadersFromResponse(AbstractResponse $response) {
        foreach ($response->getAllHeaders() as $headerName => $headerValue) {
            header("${headerName}: $headerValue");
        }
    }

    /**
     * @param $paramName
     * @param $queryStringParams
     * @return mixed
     * @throws BadRequestException
     * @throws RequestHandlingException
     */
    private function getQueryStringParameterValue($paramName, $queryStringParams) {
        if (array_key_exists($paramName, $queryStringParams)) {
            $parameterRequirementsInformation = $queryStringParams[$paramName];
            array_merge(array(
                "required" => true,
                "default" => null
            ), $parameterRequirementsInformation);
            if (!$this->currentRequest->hasQueryStringParameter($paramName) && $parameterRequirementsInformation['required']) {
                throw new BadRequestException("The action requires the query string parameter $paramName");
            } else if (!$this->currentRequest->hasQueryStringParameter($paramName)) { // Not required, we'll use a default
                $paramValue = $parameterRequirementsInformation['default'];
                return $paramValue;
            }

            $paramValue = $this->currentRequest->getQueryStringParameter($paramName);
            return $paramValue;

        } else {
            throw new RequestHandlingException("Unsure what to do with parameter $paramName, check your getQueryParametersForAction implementation");
        }
    }

    private function getStatusTextForCode($statusCode) {
        $texts = array(100 => "Continue", 101 => "Switching Protocols", 102 => "Processing", 200 => "OK", 201 => "Created", 202 => "Accepted", 203 => "Non-Authoritative Information", 204 => "No Content", 205 => "Reset Content", 206 => "Partial Content", 207 => "Multi-Status", 300 => "Multiple Choices", 301 => "Moved Permanently", 302 => "Found", 303 => "See Other", 304 => "Not Modified", 305 => "Use Proxy", 306 => "(Unused)", 307 => "Temporary Redirect", 308 => "Permanent Redirect", 400 => "Bad Request", 401 => "Unauthorized", 402 => "Payment Required", 403 => "Forbidden", 404 => "Not Found", 405 => "Method Not Allowed", 406 => "Not Acceptable", 407 => "Proxy Authentication Required", 408 => "Request Timeout", 409 => "Conflict", 410 => "Gone", 411 => "Length Required", 412 => "Precondition Failed", 413 => "Request Entity Too Large", 414 => "Request-URI Too Long", 415 => "Unsupported Media Type", 416 => "Requested Range Not Satisfiable", 417 => "Expectation Failed", 418 => "I'm a teapot", 419 => "Authentication Timeout", 420 => "Enhance Your Calm", 422 => "Unprocessable Entity", 423 => "Locked", 424 => "Failed Dependency", 424 => "Method Failure", 425 => "Unordered Collection", 426 => "Upgrade Required", 428 => "Precondition Required", 429 => "Too Many Requests", 431 => "Request Header Fields Too Large", 444 => "No Response", 449 => "Retry With", 450 => "Blocked by Windows Parental Controls", 451 => "Unavailable For Legal Reasons", 494 => "Request Header Too Large", 495 => "Cert Error", 496 => "No Cert", 497 => "HTTP to HTTPS", 499 => "Client Closed Request", 500 => "Internal Server Error", 501 => "Not Implemented", 502 => "Bad Gateway", 503 => "Service Unavailable", 504 => "Gateway Timeout", 505 => "HTTP Version Not Supported", 506 => "Variant Also Negotiates", 507 => "Insufficient Storage", 508 => "Loop Detected", 509 => "Bandwidth Limit Exceeded", 510 => "Not Extended", 511 => "Network Authentication Required", 598 => "Network read timeout error", 599 => "Network connect timeout error");
        return array_key_exists($statusCode, $texts) ? $texts[$statusCode] : "OK";
    }

}
