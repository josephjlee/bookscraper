<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Americanas extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://busca.americanas.com.br/busca.php?q=%s&cat=9';
        $uri = sprintf($format, urlencode($item->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
        $errorMessages = array(
            'Nenhum resultado encontrado para sua consulta',
            'não retornou nenhum resultado',
        );

        foreach ($errorMessages as $errorMessage) {
            if (strpos($content, $errorMessage) !== false) {
                return $result;
            }
        }

        $url = $crawler->filter('.list .url')->link()->getUri();
        $url = preg_replace('/^.*link=([^&]+).*$/', '$1', $url);
        $crawler = $this->_createCrawler($url, $content);
        $priceText = $crawler->filter('strong .price')->text();
        $price = $this->_parsePrice($priceText);

        $result->setPrice($price)
               ->setUrl($url);

        return $result;
    }
}