<?php

namespace SplitBill\Entity;

/**
 * POPO to encapsulate a TodoItem.
 *
 * @package SplitBill\Entity
 */
class TodoItem {

    private $title;
    private $completed;

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getCompleted() {
        return $this->completed;
    }
}
