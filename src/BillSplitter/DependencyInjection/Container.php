<?php

namespace BillSplitter\DependencyInjection;

use ReflectionClass;
use BillSplitter\Exception\ContainerException;

/**
 * Simple IoC container.
 *
 * @author Adam Williams
 * @package BillSplitter\DependencyInjection
 */
class Container implements IContainer {

    /**
     * @var array Associative array of interface to class.
     */
    private $abstractToConcreteBindings = array();

    /**
     * @var array Associative array of class name to instance;
     */
    private $singletonsMap = array();

    /**
     * Container constructor.
     */
    public function __construct() {
        $this->singletonsMap = array("\\BillSplitter\\DependencyInjection\\Container" => $this);
        $this->registerAbstractImplementation("\\BillSplitter\\DependencyInjection\\IContainer", "\\BillSplitter\\DependencyInjection\\Container");
    }

    public function resolveClassInstance($class) {
        $rc = new ReflectionClass($class);

        return $this->getSingletonOrInstantiate($rc);
    }

    private function normaliseName($className) {
        if (substr($className, 0, 2) !== "\\") {
            $className = "\\" . $className;
        }
        return $className;
    }

    private function getSingletonOrInstantiate(ReflectionClass $rc) {
        if ($rc->isInterface()) {
            $rc = $this->getClassCorrespondingToInterface($rc);
        }

        $normalisedName = $this->normaliseName($rc->getName());
        if (array_key_exists($normalisedName, $this->singletonsMap)) {
            return $this->singletonsMap[$normalisedName];
        }

        $constructorMethod = $rc->getConstructor();

        if ($constructorMethod === null || $constructorMethod->getNumberOfRequiredParameters() == 0) {
            return $rc->newInstance();
        } else {
            $params = array();
            foreach ($constructorMethod->getParameters() as $param) {
                if ($param->isOptional()) {
                    $params[] = $param->getDefaultValue();
                    continue;
                }
                if ($param->getClass() === null) {
                    throw new ContainerException("I can't deal with constructor parameters without type-hints");
                }
                $params[] = $this->getSingletonOrInstantiate($param->getClass());
            }
            return $rc->newInstanceArgs($params);
        }
    }

    private function getClassCorrespondingToInterface(ReflectionClass $interface) {
        $fullName = $this->normaliseName($interface->getName());

        if (!array_key_exists($fullName, $this->abstractToConcreteBindings)) {
            throw new ContainerException("There's no concrete class bound to the interface " . $fullName . " when in state " . $this->__toString());
        }
        return new ReflectionClass($this->abstractToConcreteBindings[$fullName]);
    }

    public function registerAbstractImplementation($abstract, $concrete) {
        if (array_key_exists($abstract, $this->abstractToConcreteBindings)) {
            throw new ContainerException("Binding already exists for $abstract");
        }
        $this->abstractToConcreteBindings[$abstract] = $concrete;
    }

    public function registerSingleton($instance) {
        $fullClassName = $this->normaliseName(get_class($instance));

        if (array_key_exists($fullClassName, $this->singletonsMap)) {
            throw new ContainerException("Binding already exists for $fullClassName");
        }

        $this->singletonsMap[$fullClassName] = $instance;
    }

    /**
     * @return string
     */
    public function __toString() {
        return json_encode(array("abstract-to-concrete" => $this->abstractToConcreteBindings, "singletons" => array_keys($this->singletonsMap)));
    }

    /**
     * @return array
     */
    public function getInterfaceBindings() {
        return $this->abstractToConcreteBindings;
    }

    /**
     * @return array
     */
    public function getSingletonNames() {
        $names = array();
        foreach ($this->singletonsMap as $item) {
            $names[] = get_class($item);
        }
        return $names;
    }
}
