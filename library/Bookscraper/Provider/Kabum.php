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
 * KaBuM! provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Kabum extends ProviderAbstract
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
        $format = 'http://www.kabum.com.br'
                . '/cgi-local/kabum3/site/listagem.cgi?string=%s';

        $title = utf8_decode($item->getTitle());
        $uri = sprintf($format, urlencode($title));
        $falseAlarm = 'Lamentamos, nenhum produto';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            /* @var $crawler \Symfony\Component\DomCrawler\Crawler */

            // @TODO Use $node to filter like this.
            $crawler = $crawler->filter('table[width="95%"]')->eq(0);
            $falseAlarmSelector = 'img[src="http://www.kabum.com.br/kabum3/'
                                . 'imagem/listagem/list_icone-indisponivel.png"]';

            if (count($crawler->filter($falseAlarmSelector)) === 0) {
                $url = $crawler->filter('a')->link()->getUri();
                $priceText = $crawler->filter('font[color="#FF0000"]')->text();
                $price = $this->parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}
