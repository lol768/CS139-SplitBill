<?php

namespace SplitBill\Session;
use SplitBill\IApplication;

/**
 * Flash session items.
 *
 * @package SplitBill\Session
 */
class FlashSession extends UserSession implements IFlashSession {

    /**
     * Only call me once!
     * @param IApplication $app
     */
    public function __construct(IApplication $app) {
        parent::__construct($app);
        $this->cleanupOldFlashItems();
    }

    public function get($key) {
        return parent::get("__flash_$key");
    }

    public function set($key, $value) {
        parent::set("__flash_$key", $value);
    }

    public function has($key) {
        return (array_key_exists("__flash_$key", $_SESSION));
    }

    public function remove($key) {
        parent::remove("__flash_$key");
    }

    private function addToFlashSessionDeleteList($key) {
        $currentList = parent::get("__flash");
        if ($currentList === null) {
            $currentList = array($key);
        } else {
            $currentList[] = $key;
        }
        parent::set("__flash", $currentList);
    }

    private function removeFromFlashSessionDeleteList($key) {
        $currentList = parent::get("__flash");
        if ($currentList === null) {
            return;
        } else {
            $key = array_search("__flash_" . $key, $currentList);
            if($key !== false) {
                unset($currentList[$key]);
            }
        }
        parent::set("__flash", $currentList);
    }

    public function cleanupOldFlashItems() {
        $currentList = parent::get("__flash");
        $items = array();

        if ($currentList !== null) {
            $items = $currentList;
        }

        foreach ($items as $item) {
            parent::remove($item);
        }

        parent::set("__flash", array());

        foreach (array_keys($_SESSION) as $key) {
            if (substr($key, 0, 8) === "__flash_") {
                $this->addToFlashSessionDeleteList($key);
            }
        }
    }

    public function getOrDefault($key, $default) {
        return parent::getOrDefault($key, $default);
    }

    public function extendLife($key) {
        $this->removeFromFlashSessionDeleteList($key);
    }
}
