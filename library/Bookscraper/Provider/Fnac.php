<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Fnac extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://busca.fnac.com.br/'
                . '?busca=%s&filtro=product_type%%3ALivro';

        $uri = sprintf($format, urlencode($item->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
        $errorMessage = '<strong>0</strong>';

        if (strpos($content, $errorMessage) === false) {
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