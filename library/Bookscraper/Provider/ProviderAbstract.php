<?php

namespace Bookscraper\Provider;

abstract class ProviderAbstract implements ProviderInterface
{
    /**
     * @param  string $uri
     * @param  string|null $content
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function _createCrawler($uri, &$contentReference = null)
    {
        /**
         * @see \Dz_Http_Client
         */
        require_once __DIR__ . '/../../Dz/Http/Client.php';

        $content = \Dz_Http_Client::getData($uri);
        $crawler = new \Symfony\Component\DomCrawler\Crawler(null, $uri);

        $crawler->addContent($content, 'text/html');

        if (isset($contentReference)) {
            $contentReference = $content;
        }

        return $crawler;
    }

    /**
     * @param  string $priceText
     * @param  string $prefix
     * @return float
     */
    protected function _parsePrice($priceText, $prefix = null)
    {
        if ($prefix !== null) {
            $prefix .= ' ';
            $priceText = str_replace($prefix, '', $priceText);
        }

        $priceText = str_replace('.', '', $priceText);
        $priceText = str_replace(',', '.', $priceText);

        return (float) $priceText;
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