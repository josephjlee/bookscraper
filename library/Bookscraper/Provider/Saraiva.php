<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class Saraiva extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://busca.livrariasaraiva.com.br/'
                . 'search?w=%s&af=cat1%%3alivros';

        $uri = sprintf($format, urlencode($item->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
        $errorMessage = 'N&atilde;o foram encontrados resultados para '
                      . 'todas as palavras da sua pesquisa.';

        if (strpos($content, $errorMessage) === false) {
            $url = $crawler->filter('.sli_grid_result > a')->link()->getUri();
            $url = urldecode(preg_replace('/^.*url=([^&]+).*$/', '$1', $url));
            $priceText = $crawler->filter('.precoPor')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}