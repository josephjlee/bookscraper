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
 * Americanas.com provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Americanas extends ProviderAbstract
{
    /**
     * Books category const.
     *
     * @var string
     */
    const CATEGORY_BOOKS = '9';

    /**
     * Electroportables category const.
     *
     * @var string
     */
    const CATEGORY_ELECTROPORTABLES = '1335';

    /**
     * Search category.
     *
     * @var string
     */
    protected $category;

    /**
     * Public constructor.
     *
     * @param string $category
     */
    public function __construct($category = null)
    {
        $this->category = $category;
    }

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
        $format = 'http://busca.americanas.com.br/busca.php?q=%s';

        if ($this->category !== null) {
            $format .= '&cat=' . $this->category;
        }

        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
        $falseAlarms = array(
            'Nenhum resultado encontrado para sua consulta',
            'não retornou nenhum resultado',
        );

        if (($crawler = $crawlerFactory->create($uri, $falseAlarms)) !== null) {
            $url = $crawler->filter('.list .url')->link()->getUri();
            $url = preg_replace('/^.*link=([^&]+).*$/', '$1', $url);

            if (($crawler = $crawlerFactory->create($url)) !== null) {
                $priceText = $crawler->filter('strong .price')->text();
                $price = $this->parsePrice($priceText);

                $result->setPrice($price)
                       ->setUrl($url);
            }
        }

        return $result;
    }
}
