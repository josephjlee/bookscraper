<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class LivrariaCultura extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://www.livrariacultura.com.br/scripts/busca/busca.asp'
                . '?avancada=1&titem=1&palavratitulo=%s&modobuscatitulo=pc'
                . '&palavraautor=%s&modobuscaautor=pc'
                . '&cidioma=POR&ordem=disponibilidade';

        $forbiddenChars = array('#', '&', '(', ')', '*', '\'', '"', '-', '_');
        $title = str_replace($forbiddenChars, '', $item->getTitle());
        $author = str_replace($forbiddenChars, '', $item->getAuthor());
        $uri = sprintf($format, urlencode($title), urlencode($author));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
        $errorMessage = 'nenhum resultado correspondente';

        if (strpos($content, $errorMessage) === false) {
            $linkSelector = '.listaProduto .img_capa a';
            $url = $crawler->filter($linkSelector)->link()->getUri();
            $priceText = $crawler->filter('.listaProduto .preco')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}