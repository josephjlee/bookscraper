<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class LivrariaDaFolha extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://livraria.folha.com.br/busca'
                . '?availability=yes&author=%s&q=%s';

        $title = utf8_decode($item->getTitle());
        $author = utf8_decode($item->getAuthor());
        $uri = sprintf($format, urlencode($author), urlencode($title));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
        $errorMessage = 'Nenhum produto encontrado.';

        if (strpos($content, $errorMessage) === false) {
            $url = $crawler->filter('.title a')->link()->getUri();
            $priceText = $crawler->filter('.redPrice')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}