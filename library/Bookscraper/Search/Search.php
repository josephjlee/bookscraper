<?php

namespace Bookscraper\Search;

use Bookscraper\Cache\Driver\DriverInterface;
use Bookscraper\Search\Item;
use Bookscraper\Provider\ProviderInterface;

class Search
{
    /**
     * Cache driver.
     *
     * @var DriverInterface
     */
    protected $_cacheDriver;

    /**
     * Public constructor.
     *
     * @param DriverInterface $cacheDriver
     */
    public function __construct(DriverInterface $cacheDriver = null)
    {
        $this->_cacheDriver = $cacheDriver;
    }

    /**
     * Gets cache driver.
     *
     * @return DriverInterface
     */
    public function getCacheDriver()
    {
        return $this->_cacheDriver;
    }

    public function lookup(ProviderInterface $provider, Item $item)
    {
        if ($this->_cacheDriver instanceof DriverInterface) {
            $key = $item->getKey(get_class($provider));
            $callback = function () use ($provider, $item) {
                return $provider->lookup($item);
            };

            $result = $this->_cacheDriver->get($key, $callback);
        } else {
            $result = $provider->lookup($item);
        }

        $result->setItem($item)
               ->setProvider($provider);

        return $result;
    }

    /**
     * Sets cache driver.
     *
     * @param  DriverInterface $cacheDriver
     * @return Search
     */
    public function setCacheDriver(DriverInterface $cacheDriver)
    {
        $this->_cacheDriver = $cacheDriver;

        return $this;
    }
}