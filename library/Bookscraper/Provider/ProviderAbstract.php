<?php

namespace Bookscraper\Provider;

abstract class ProviderAbstract implements ProviderInterface
{
    /**
     * @param  string $uri
     * @param  string|null $content
     * @param  array $options \Dz\Http\Client options.
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function _createCrawler(
        $uri, &$contentReference = null, array $options = array()
    ) {
        $cacheDirectory = 'data/cache/';
        $cacheDriver = new \Bookscraper\Cache\Driver\File($cacheDirectory);
        $key = base64_encode($uri);
        $content = $cacheDriver->get($key, function () use ($uri, $options) {
            return \Dz\Http\Client::getData($uri, $options);
        });

        $crawler = new \Symfony\Component\DomCrawler\Crawler(null, $uri);

        $crawler->addContent($content, 'text/html');

        if (isset($contentReference)) {
            $contentReference = $content;
        }

        return $crawler;
    }

    /**
     * @param  string $priceText
     * @return float
     */
    protected function _parsePrice($priceText)
    {
        $price = preg_replace('/^\D*(\d+)[\.,](\d+)$/', '$1.$2', $priceText);

        return (float) $price;
    }
}