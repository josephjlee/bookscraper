<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class LivrariaDaFolha extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://livraria.folha.com.br/busca'
                . '?availability=yes&author=%s&q=%s';

        $title = utf8_decode($item->getTitle());
        $author = utf8_decode($item->getAuthor());
        $uri = sprintf($format, urlencode($author), urlencode($title));
        $falseAlarm = 'Nenhum produto encontrado.';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
            $url = $crawler->filter('.title a')->link()->getUri();
            $priceText = $crawler->filter('.redPrice')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}