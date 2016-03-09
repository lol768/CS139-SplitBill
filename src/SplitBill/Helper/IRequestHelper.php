<?php


namespace SplitBill\Helper;


use SplitBill\Request\HttpRequest;

interface IRequestHelper {

    /**
     * Gets an instance of the current HttpRequest based on the context of the current request.
     *
     * @return HttpRequest Object for the current request instance.
     */
    public function getCurrentRequestInstance();

}
