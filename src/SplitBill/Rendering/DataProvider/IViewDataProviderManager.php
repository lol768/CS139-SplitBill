<?php

namespace SplitBill\Rendering\DataProvider;

/**
 * Manages view data providers.
 *
 * @package SplitBill\Rendering\DataProvider
 */
interface IViewDataProviderManager {

    /**
     * Registers a new view data provider to use when rendering views.
     *
     * @param IViewDataProvider $provider The view data provider to register.
     * @return void
     */
    public function registerProvider(IViewDataProvider $provider);

    /**
     * Unregisters a previously registered view data provider.
     *
     * @param IViewDataProvider $provider The view data provider to unregister.
     * @return void
     */
    public function unregisterProvider(IViewDataProvider $provider);

    /**
     * @return IViewDataProvider[] The registered view data providers.
     */
    public function getAllProviders();

}
