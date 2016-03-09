<?php


namespace SplitBill;


use SplitBill\DependencyInjection\IContainer;

interface IApplication {

    /**
     * @return IContainer The IoC container implementation instance.
     */
    public function getContainer();

    /**
     * @return string Absolute path to the root directory.
     */
    public function getRootPath();

    /**
     * @return array Application's config as an associative array
     */
    public function getConfig();

}
