<?php


namespace BillSplitter\Helper;


use BillSplitter\Request\HttpRequest;

interface IRequestHelper {

    /**
     * Gets an instance of the current HttpRequest based on the context of the current request.
     *
     * @return HttpRequest Object for the current request instance.
     */
    public function getCurrentRequestInstance();

}
