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
 * Lojas Colombo provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Colombo extends ProviderAbstract
{
    /**
     * Lookup for an item.
     *
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     * @todo   Check for unavailable items.
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://www.colombo.com.br/pesquisa?termo=%s';
        $title = utf8_decode($item->getTitle());
        $uri = sprintf($format, urlencode($title));
        $falseAlarm = 'não encontrou nenhum resultado';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            // Check for redirects.
            if (count($crawler->filter('.codigoProduto')) === 0) {
                $priceText = $crawler->filter('.precoPor')->eq(1)->text();
                $url = $crawler->filter('.produtoTit a')->link()->getUri();
            } else {
                $priceText = $crawler->filter('#precoPor')->text();
                $url = $uri;
            }

            $price = $this->parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}
