<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Fnac extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://busca.fnac.com.br/'
                . '?busca=%s&filtro=product_type%%3ALivro';

        $uri = sprintf($format, urlencode($item->getTitle()));
        $falseAlarm = '<strong>0</strong>';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            $url = $crawler->filter('.itemPesquisaNome a')->link()->getUri();
            $url = urldecode(preg_replace('/^.*url=([^&]+).*$/', '$1', $url));
            $priceText = $crawler->filter('.precoPorValor')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}