<?php

namespace SplitBill\Rendering\DataProvider;

use SplitBill\Exception\DataProviderException;

class ViewDataProviderManager implements IViewDataProviderManager {

    /**
     * @var IViewDataProvider[]
     */
    private $store = array();

    /**
     * Registers a new view data provider to use when rendering views.
     *
     * @param IViewDataProvider $provider The view data provider to register.
     * @return void
     */
    public function registerProvider(IViewDataProvider $provider) {
        $this->store[] = $provider;
    }

    /**
     * Unregisters a previously registered view data provider.
     *
     * @param IViewDataProvider $provider The view data provider to unregister.
     * @throws DataProviderException If the provided provider isn't already in the provider store.
     */
    public function unregisterProvider(IViewDataProvider $provider) {
        $key = array_search($provider, $this->store);
        if ($key === false) {
            throw new DataProviderException("Attempt to unregister a data provider not in the provider store.");
        }
        unset($this->store[$key]);
    }

    /**
     * @return IViewDataProvider[] The registered view data providers.
     */
    public function getAllProviders() {
        return $this->store;
    }
}
