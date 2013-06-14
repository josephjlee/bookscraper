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
 * Fnac provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Fnac extends ProviderAbstract
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
        $format = 'http://busca.fnac.com.br/'
                . '?busca=%s&filtro=product_type%%3ALivro';

        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
        $falseAlarm = '<strong>0</strong>';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            $url = $crawler->filter('.itemPesquisaNome a')->link()->getUri();
            $url = urldecode(preg_replace('/^.*url=([^&]+).*$/', '$1', $url));
            $priceText = $crawler->filter('.precoPorValor')->text();
            $price = $this->parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}
