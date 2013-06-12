<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Saraiva extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://busca.livrariasaraiva.com.br/'
                . 'search?w=%s&af=cat1%%3alivros';

        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
        $falseAlarm = 'N&atilde;o foram encontrados resultados para '
                    . 'todas as palavras da sua pesquisa.';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            /* @var $crawler \Symfony\Component\DomCrawler\Crawler */

            // @TODO Use $node to filter like this.
            $crawler = $crawler->filter('.sli_grid_result')->eq(0);
            $falseAlarmSelector = 'img[src="http://www.livrariasaraiva.com.br'
                                . '/imgc/botoes/btn_avise.jpg"]';

            if (count($crawler->filter($falseAlarmSelector)) === 0) {
                $url = $crawler->filter('a')->link()->getUri();
                $url = urldecode(preg_replace('/^.*url=([^&]+).*$/', '$1', $url));
                $priceText = $crawler->filter('.precoPor')->text();
                $price = $this->_parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}