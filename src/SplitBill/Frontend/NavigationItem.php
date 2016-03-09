<?php

namespace SplitBill\Frontend;

/**
 * POPO.
 *
 * @package SplitBill\Frontend
 */
class NavigationItem {

    private $active;
    private $link;
    private $text;

    /**
     * NavigationItem constructor.
     * @param $active
     * @param $link
     * @param $text
     */
    public function __construct($text, $link, $active=false) {
        $this->active = $active;
        $this->link = $link;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getClass() {
        if ($this->active) {
            return "active";
        }
        return "";
    }

    /**
     * @param boolean $active
     */
    public function setActive($active) {
        $this->active = $active;
    }
}
