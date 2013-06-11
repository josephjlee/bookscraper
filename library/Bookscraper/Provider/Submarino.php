<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Submarino extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://busca.submarino.com.br/busca.php?q=%s&cat=460';
        $uri = sprintf($format, urlencode($item->getTitle()));
        $falseAlarms = array(
            'Desculpe, no momento não temos  esse produto',
            'não encontrou nenhum resultado',
        );

        if (($crawler = $crawlerFactory->create($uri, $falseAlarms)) !== null) {
            $url = $crawler->filter('.list .url')->link()->getUri();
            $url = preg_replace('/^.*link=([^&]+).*$/', '$1', $url);
            $falseAlarm = 'Ops, já vendemos todo o estoque!';

            if (($crawler = $crawlerFactory->create($url, $falseAlarm)) !== null) {
                $priceText = $crawler->filter('strong .amount')->text();
                $price = $this->_parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}