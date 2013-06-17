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
 * Magazine Luiza provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class MagazineLuiza extends ProviderAbstract
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
        $format = 'http://www.magazineluiza.com.br/busca/%s/';
        $uri = sprintf($format, urlencode($item->getTitle()));
        $falseAlarm = 'não encontramos o que você procura';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            /* @var $crawler \Symfony\Component\DomCrawler\Crawler */

            // @TODO Use $node to filter like this.
            $crawler = $crawler->filter('.productShowCase li')->eq(0);
            $falseAlarmSelector = '.soldOut';

            if (count($crawler->filter($falseAlarmSelector)) === 0) {
                $url = $crawler->filter('a')->link()->getUri();
                $priceText = $crawler->filter('.price')->text();
                $price = $this->parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}
