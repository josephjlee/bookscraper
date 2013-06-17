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
 * Ponto Frio provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class PontoFrio extends ProviderAbstract
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
        $format = 'http://search.pontofrio.com.br'
                . '/search?w=%s&af=dept:eletroportateis';

        $uri = sprintf($format, urlencode($item->getTitle()));
        $falseAlarm = 'não encontrou nenhum resultado';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            $url = $crawler->filter('.link')->attr('title');
            $falseAlarm = 'Produto temporariamente indispon&#237;vel.';

            if (($crawler = $crawlerFactory->create($url, $falseAlarm)) !== null) {
                $priceText = $crawler->filter('.price')->text();
                $price = $this->parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}
