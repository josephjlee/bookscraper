<?php

namespace Bookscraper\Provider;

abstract class ProviderAbstract implements ProviderInterface
{
    /**
     * @param  string $uri
     * @param  string|null $content
     * @param   array $extraOptions Dz_Http_Client extra options.
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function _createCrawler(
        $uri, &$contentReference = null, array $extraOptions = array()
    ) {
        /**
         * @see \Dz_Http_Client
         */
        require_once __DIR__ . '/../../Dz/Http/Client.php';

        $content = \Dz_Http_Client::getData($uri, $extraOptions);
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

    /**
     * Gets provider name.
     *
     * @return string
     */
    public function getName()
    {
        $className = get_class($this);

        return preg_replace('/^.*\\\/', '', $className);
    }
}