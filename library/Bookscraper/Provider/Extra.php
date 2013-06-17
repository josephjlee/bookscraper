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
 * Extra provider.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Extra extends ProviderAbstract
{
    /**
     * Books category const.
     *
     * @var string
     */
    const CATEGORY_BOOKS = 'livros';

    /**
     * Electroportables category const.
     *
     * @var string
     */
    const CATEGORY_ELECTROPORTABLES = 'eletroportateis';

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
        $format = 'http://buscando.extra.com.br/search?w=%s';

        if ($this->category !== null) {
            $format .= '&af=dept:' . $this->category;
        }

        $query = $item->getAuthor() . ' ' . $item->getTitle();
        $uri = sprintf($format, urlencode($query));
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
