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
    public function __construct(IApplication $app) { // TODO: eventually depend on session stuff here
        $this->leftCollection = $this->getPublicItemsFromConfig($app->getConfig());
        $this->rightCollection = array(new NavigationItem("Login", "login.html"), new NavigationItem("Register", "register.html"));
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
    private function getPublicItemsFromConfig($config) {
        $arr = array();
        foreach ($config['public_nav'] as $name => $link) {
            $arr[] = new NavigationItem($name, $link);
        }
        return $arr;
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
}
