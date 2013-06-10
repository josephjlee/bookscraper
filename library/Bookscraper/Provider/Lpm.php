<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Lpm extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://www.lpm.com.br/livros/layout_buscaprodutos.asp'
                . '?Tipo=Livros&FiltroTitulo=%s&FiltroAutor=%s';

        $title = utf8_decode($item->getTitle());
        $author = utf8_decode($item->getAuthor());
        $uri = sprintf($format, urlencode($title), urlencode($author));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
        $errorMessage = 'Nenhum livro foi encontrado';

        if (strpos($content, $errorMessage) === false) {
            $url = $crawler->filter('h2 a')->link()->getUri();
            $priceText = $crawler->filter('.p2 b')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}