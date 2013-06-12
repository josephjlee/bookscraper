<?php

namespace Bookscraper\Search;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Provider\ProviderInterface;

class Search
{
    /**
     * Crawler factory.
     *
     * @var CrawlerFactory
     */
    protected $_crawlerFactory;

    /**
     * Public constructor.
     */
    public function __construct(CrawlerFactory $crawlerFactory)
    {
        $this->_crawlerFactory = $crawlerFactory;
    }

    public function lookup(ProviderInterface $provider, Item $item)
    {
        try {
            return $provider->lookup($item, $this->_crawlerFactory)
                            ->setItem($item)
                            ->setProvider($provider);
        } catch (\Exception $exception) {
            return null;
        }
    }
}