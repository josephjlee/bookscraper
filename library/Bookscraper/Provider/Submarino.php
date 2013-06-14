<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

/**
 * Submarino provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Submarino extends ProviderAbstract
{
    /**
     * Lookup for an item.
     *
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://busca.submarino.com.br/busca.php?q=%s&cat=460';
        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
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
                $price = $this->parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}
