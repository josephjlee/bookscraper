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
 * Balão da Informática provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class BalaoDaInformatica extends ProviderAbstract
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
        $uri = 'https://www.balaodainformatica.com.br'
             . '/site/index.asp?pagina=buscaprod';

        $falseAlarm = 'Nenhum produto encontrado!';
        $options = array(
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => http_build_query(
                array(
                    'busca' => $item->getTitle(),
                )
            ),
        );

        $crawler = $crawlerFactory->create($uri, $falseAlarm, $options);

        if ($crawler !== null) {
            /* @var $crawler \Symfony\Component\DomCrawler\Crawler */

            // @TODO Use $node to filter like this.
            $crawler = $crawler->filter('#table1 td[width="33%"]')->eq(0);
            $falseAlarmSelector = 'img[src="img/aviseme.gif"]';

            if (count($crawler->filter($falseAlarmSelector)) === 0) {
                $url = $crawler->filter('a')->link()->getUri();
                $priceText = $crawler->filter('p font b font')->text();
                $price = $this->parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}
