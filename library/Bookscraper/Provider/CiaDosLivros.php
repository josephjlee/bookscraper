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
 * Cia dos Livros provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class CiaDosLivros extends ProviderAbstract
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
        $baseUrl = 'http://www.ciadoslivros.com.br/pesquisa/';
        $format = $baseUrl . '?p=%s';
        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
        $falseAlarms = array(
            '&atilde;o encontrou resultados',
            '<span class="availability" style="display:none">out_of_stock</span>',
        );

        if (($crawler = $crawlerFactory->create($uri, $falseAlarms)) !== null) {
            // Check for redirects.
            $url = $crawler->filter('link[rel="canonical"]')->attr('href');
            $searchPattern = '/^' . preg_quote($baseUrl, '/') . '/';

            if (preg_match($searchPattern, $url) > 0) {
                $url = $crawler->filter('h3 a')->link()->getUri();
            }

            $priceText = $crawler->filter('.sale')->text();
            $price = $this->parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}
