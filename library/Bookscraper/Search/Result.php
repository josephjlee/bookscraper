<?php

namespace Bookscraper\Search;

use Bookscraper\Provider\ProviderInterface;
use Bookscraper\Search\Item;

class Result
{
    /**
     * Search item.
     *
     * @var Item
     */
    protected $_item;

    /**
     * Product price.
     *
     * @var float
     */
    protected $_price;

    /**
     * Provider used in the search.
     *
     * @var ProviderInterface
     */
    protected $_provider;

    /**
     * Product URL.
     *
     * @var string
     */
    protected $_url;

    /**
     * Gets search item.
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Gets product price.
     *
     * @return float|null
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * Gets provider used in the search.
     *
     * @return ProviderInterface
     */
    public function getProvider()
    {
        return $this->_provider;
    }

    /**
     * Gets product URL.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * Checks whether result is cheaper than $result.
     *
     * @param  Result|null $result
     * @return boolean
     */
    public function isCheaperThan(Result $result = null)
    {
        return $this->isNotEmpty()
            && ($result === null || $this->getPrice() < $result->getPrice());
    }

    /**
     * Checks whether result contains price and URL.
     *
     * @return boolean
     */
    public function isNotEmpty()
    {
        return $this->_price !== null && $this->_url !== null;
    }

    /**
     * Sets search item.
     *
     * @param  Item $item
     * @return Result
     */
    public function setItem(Item $item)
    {
        $this->_item = $item;

        return $this;
    }

    /**
     * Sets product price.
     *
     * @param  float|null $price
     * @return Result
     */
    public function setPrice($price)
    {
        $this->_price = $price;

        return $this;
    }

    /**
     * Sets provider used in the search.
     *
     * @param  ProviderInterface $provider
     * @return Result
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->_provider = $provider;

        return $this;
    }

    /**
     * Sets product URL.
     *
     * @param  string|null $url
     * @return Result
     */
    public function setUrl($url)
    {
        $this->_url = $url;

        return $this;
    }
}