<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Americanas extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://busca.americanas.com.br/busca.php?q=%s&cat=9';
        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
        $falseAlarms = array(
            'Nenhum resultado encontrado para sua consulta',
            'não retornou nenhum resultado',
        );

        if (($crawler = $crawlerFactory->create($uri, $falseAlarms)) !== null) {
            $url = $crawler->filter('.list .url')->link()->getUri();
            $url = preg_replace('/^.*link=([^&]+).*$/', '$1', $url);

            if (($crawler = $crawlerFactory->create($url)) !== null) {
                $priceText = $crawler->filter('strong .price')->text();
                $price = $this->_parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}