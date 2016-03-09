<?php

namespace SplitBill\DependencyInjection;

/**
 * Simple IoC container.
 *
 * @author Adam Williams
 * @package SplitBill\DependencyInjection
 */
interface IContainer {

    /**
     * Resolve a class instance out of the container.
     *
     * @param string $class
     * @return object
     */
    public function resolveClassInstance($class);

    /**
     * Bind an interface to an implementation.
     *
     * @param string $abstract The interface to bind.
     * @param string $concrete The concrete class to bind it to.
     * @return void
     */
    public function registerAbstractImplementation($abstract, $concrete);

    /**
     * Register a singleton class instance with the container.
     *
     * @param object $instance The object instance.
     * @return void
     */
    public function registerSingleton($instance);

    /**
     * @return array
     */
    public function getInterfaceBindings();

    /**
     * @return array
     */
    public function getSingletonNames();

}
