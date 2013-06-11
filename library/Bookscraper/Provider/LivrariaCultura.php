<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class LivrariaCultura extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @param  CrawlerFactory $crawlerFactory
     * @return Result
     */
    public function lookup(Item $item, CrawlerFactory $crawlerFactory)
    {
        $result = new Result();
        $format = 'http://www.livrariacultura.com.br/scripts/busca/busca.asp'
                . '?avancada=1&titem=1&palavratitulo=%s&modobuscatitulo=pc'
                . '&palavraautor=%s&modobuscaautor=pc'
                . '&cidioma=POR&ordem=disponibilidade';

        $forbiddenChars = array('#', '&', '(', ')', '*', '\'', '"', '-', '_');
        $title = str_replace($forbiddenChars, '', $item->getTitle());
        $author = str_replace($forbiddenChars, '', $item->getAuthor());
        $uri = sprintf($format, urlencode($title), urlencode($author));
        $falseAlarm = 'nenhum resultado correspondente';

        if (($crawler = $crawlerFactory->create($uri, $falseAlarm)) !== null) {
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