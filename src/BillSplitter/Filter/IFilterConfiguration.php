<?php

namespace BillSplitter\Filter;

use BillSplitter\Filter\Post\IPostResponseFilter;
use BillSplitter\Filter\Pre\IPreResponseFilter;

interface IFilterConfiguration {

    /**
     * Add a filter to be executed *after* the response is given to use by the controller.
     *
     * @param IPostResponseFilter $filter The filter to add.
     * @return void
     */
    public function addPostFilter(IPostResponseFilter $filter);

    /**
     * @return IPostResponseFilter[] The array of post response filters to apply.
     */
    public function getPostFilters();

    /**
     * Add a filter to be executed *before* the controller action is executed.
     *
     * @param IPreResponseFilter $filter The filter to add.
     * @return void
     */
    public function addPreFilter(IPreResponseFilter $filter);

    /**
     * @return IPreResponseFilter[] The array of pre response filters to apply.
     */
    public function getPreFilters();

}
