<?php

namespace Bookscraper\Search;

use Bookscraper\Provider\ProviderInterface;

class Result
{
    /**
     * Product price.
     *
     * @var float
     */
    protected $_price;

    /**
     * Provider used in the search.
     *
     * @var \Bookscraper\Provider\ProviderInterface
     */
    protected $_provider;

    /**
     * Product URL.
     *
     * @var string
     */
    protected $_url;

    /**
     * Public constructor.
     *
     * @param \Bookscraper\Provider\ProviderInterface $provider
     * @param float|null $price
     * @param string|null $url
     */
    public function __construct(
        ProviderInterface $provider, $price = null, $url = null
    ) {
        $this->_provider = $provider;
        $this->_price = $price;
        $this->_url = $url;
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
     * @return \Bookscraper\Provider\ProviderInterface
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