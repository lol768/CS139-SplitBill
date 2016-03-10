<?php

namespace SplitBill\Frontend;
use SplitBill\IApplication;

/**
 * Holds nav items for the top navbar.
 *
 * @package SplitBill\Frontend
 */
class NavigationCollection {

    /**
     * @var NavigationItem[]
     */
    private $leftCollection;

    /**
     * @var NavigationItem[]
     */
    private $rightCollection;

    /**
     * NavigationCollection constructor.
     */
    public function __construct(IApplication $app) {
        $this->leftCollection = $this->getPublicLeftItemsFromConfig($app->getConfig());
        $this->rightCollection = $this->getPublicRightItemsFromConfig($app->getConfig());
    }

    /**
     * @return NavigationItem[]
     */
    public function getLeftCollection() {
        return $this->leftCollection;
    }

    /**
     * @return NavigationItem[]
     */
    public function getRightCollection() {
        return $this->rightCollection;
    }

    /**
     * @return array The left-hand public nav items
     */
    private function getPublicLeftItemsFromConfig($config) {
        $key = "public_nav_left";
        return $this->getNavItemsFromConfigKey($config, $key);
    }

    /**
     * @return array The right-hand public nav items
     */
    private function getPublicRightItemsFromConfig($config) {
        $key = "public_nav_right";
        return $this->getNavItemsFromConfigKey($config, $key);
    }

    /**
     * @param string $name Link text
     * @return NavigationItem
     */
    public function getItemByName($name) {
        $items = array_merge($this->rightCollection, $this->leftCollection);
        $matches = array_values(array_filter($items, function (NavigationItem $item) use ($name) {
            return $item->getText() == $name;
        }));

        return count($matches) > 0 ? $matches[0] : null;
    }

    /**
     * Makes every nav item inactive.
     */
    public function setAllInactive() {
        foreach ($this->leftCollection+$this->rightCollection as $item) {
            $item->setActive(false);
        }
    }

    /**
     * @param $config
     * @param $key
     * @return array
     */
    private function getNavItemsFromConfigKey($config, $key) {
        $arr = array();
        foreach ($config[$key] as $name => $link) {
            $arr[] = new NavigationItem($name, $link);
        }
        return $arr;
    }
}
