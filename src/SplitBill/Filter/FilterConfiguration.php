<?php

namespace SplitBill\Filter;

use SplitBill\Filter\Post\IPostResponseFilter;
use SplitBill\Filter\Pre\IPreResponseFilter;

class FilterConfiguration implements IFilterConfiguration {

    private $preFilters = array();
    private $postFilters = array();

    public function addPostFilter(IPostResponseFilter $filter) {
        $this->postFilters[] = $filter;
    }

    /**
     * @return IPostResponseFilter[] The array of post response filters to apply.
     */
    public function getPostFilters() {
        return $this->postFilters;
    }

    /**
     * Add a filter to be executed *before* the controller action is executed.
     *
     * @param IPreResponseFilter $filter The filter to add.
     * @return void
     */
    public function addPreFilter(IPreResponseFilter $filter) {
        $this->preFilters[] = $filter;
    }

    /**
     * @return IPreResponseFilter[] The array of pre response filters to apply.
     */
    public function getPreFilters() {
        return $this->preFilters;
    }
}
