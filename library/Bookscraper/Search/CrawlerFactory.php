<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Search;

use Bookscraper\Cache\Driver\DriverInterface;
use Dz\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Crawler factory with cache support.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class CrawlerFactory
{
    /**
     * Cache driver.
     *
     * @var DriverInterface
     */
    protected $cacheDriver;

    /**
     * Public constructor.
     *
     * @param DriverInterface|null $cacheDriver
     */
    public function __construct(DriverInterface $cacheDriver = null)
    {
        $this->cacheDriver = $cacheDriver;
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
        $uri,
        $falseAlarms = array(),
        array $options = array()
    ) {
        $crawler = new Crawler(null, $uri);
        $callback = function () use ($uri, $options) {
            return Client::getData($uri, $options);
        };

        if ($this->cacheDriver instanceof DriverInterface) {
            $key = base64_encode($uri . serialize($options));
            $content = $this->cacheDriver->get($key, $callback);
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
        return $this->cacheDriver;
    }

    /**
     * Sets cache driver.
     *
     * @param  DriverInterface $cacheDriver
     * @return Crawler
     */
    public function setCacheDriver(DriverInterface $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;

        return $this;
    }
}
