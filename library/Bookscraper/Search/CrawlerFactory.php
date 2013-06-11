<?php

namespace Bookscraper\Search;

use Bookscraper\Cache\Driver\DriverInterface;
use Dz\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerFactory
{
    /**
     * Cache driver.
     *
     * @var DriverInterface
     */
    protected $_cacheDriver;

    /**
     * Public constructor.
     */
    public function __construct(DriverInterface $cacheDriver = null)
    {
        $this->_cacheDriver = $cacheDriver;
    }

    /**
     * Creates a crawler.
     *
     * @param  string $uri The URI of the page being crawled.
     * @param  string|array $falseAlarms False positives checks.
     * @param  array $options HTTP client options.
     * @return Crawler|null Returns null only if a false alarm is found.
     */
    public function create(
        $uri, $falseAlarms = array(), array $options = array()
    ) {
        $crawler = new Crawler(null, $uri);
        $callback = function () use ($uri, $options) {
            return Client::getData($uri, $options);
        };

        if ($this->_cacheDriver instanceof DriverInterface) {
            $key = base64_encode($uri . serialize($options));
            $content = $this->_cacheDriver->get($key, $callback);
        } else {
            $content = $callback();
        }

        if (!is_array($falseAlarms)) {
            $falseAlarms = array($falseAlarms);
        }

        foreach ($falseAlarms as $falseAlarm) {
            if (strpos($content, $falseAlarm) !== false) {
                return null;
            }
        }

        $crawler->addContent($content, 'text/html');

        return $crawler;
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

    /**
     * Sets cache driver.
     *
     * @param  DriverInterface $cacheDriver
     * @return Crawler
     */
    public function setCacheDriver(DriverInterface $cacheDriver)
    {
        $this->_cacheDriver = $cacheDriver;

        return $this;
    }
}