<?php

namespace SplitBill;

use SplitBill\DependencyInjection\IContainer;

class Application implements IApplication {

    private static $application;

    /**
     * @return IApplication The singleton application instance.
     */
    public static function getInstance() {
        return self::$application;
    }

    public static function setInstance(Application $instance) {
       self::$application = $instance;
    }

    /* --------------------------- */

    /**
     * @var float
     */
    private $startTime;

    /**
     * @var IContainer
     */
    private $container;

    /**
     * @var string Path to the root directory.
     */
    private $rootPath;

    /**
     * @var array Cache of the config from config/app.php
     */
    private $configCache;

    /**
     * Application constructor.
     * @param IContainer $container
     */
    public function __construct(IContainer $container) {
        $this->container = $container;
        $this->rootPath = dirname(dirname(__DIR__));
        $this->startTime = microtime(true);
        $this->configCache = include($this->rootPath . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "app.php");
    }

    public function getContainer() {
        return $this->container;
    }

    public function getRootPath() {
        return $this->rootPath;
    }

    /**
     * @return array Application's config as an associative array
     */
    public function getConfig() {
        return $this->configCache;
    }

    /**
     * @return float Time at which app was initialised.
     */
    public function getStartTime() {
        return $this->startTime;
    }
}
