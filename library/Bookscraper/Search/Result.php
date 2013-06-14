<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Search;

use Bookscraper\Provider\ProviderInterface;
use Bookscraper\Search\Item;

/**
 * Search result class.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Result
{
    /**
     * Search item.
     *
     * @var Item
     */
    protected $item;

    /**
     * Product price.
     *
     * @var float
     */
    protected $price;

    /**
     * Provider used in the search.
     *
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * Product URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Gets search item.
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Gets product price.
     *
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Gets provider used in the search.
     *
     * @return ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Gets product URL.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
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
        return $this->price !== null && $this->url !== null;
    }

    /**
     * Sets search item.
     *
     * @param  Item $item
     * @return Result
     */
    public function setItem(Item $item)
    {
        $this->item = $item;

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
        $this->price = $price;

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
        $this->provider = $provider;

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
        $this->url = $url;

        return $this;
    }
}
