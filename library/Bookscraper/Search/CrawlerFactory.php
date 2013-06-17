<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Search;

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
     * HTTP client.
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * Public constructor.
     *
     * @param Client|null $httpClient
     */
    public function __construct(Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client();
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
        if (($content = $this->httpClient->request($uri, $options)) === false) {
            return null;
        }

        if (!is_array($falseAlarms)) {
            $falseAlarms = array($falseAlarms);
        }

        foreach ($falseAlarms as $falseAlarm) {
            if (strpos($content, $falseAlarm) !== false) {
                return null;
            }
        }

        $crawler = new Crawler(null, $uri);

        $crawler->addContent($content, 'text/html');

        return $crawler;
    }

    /**
     * Gets HTTP client.
     *
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Sets HTTP client.
     *
     * @param  Client $httpClient
     * @return Crawler
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }
}
