<?php

namespace Bookscraper\Provider;

class Fnac extends ProviderAbstract
{
    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://busca.fnac.com.br/'
                . '?busca=%s&filtro=product_type%%3ALivro';

        $uri = sprintf($format, urlencode($search->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result();

        if (strpos($content, '<strong>0</strong>') === false) {
            $url = $crawler->filter('.itemPesquisaNome a')->link()->getUri();
            $priceText = $crawler->filter('.precoPorValor')->text();
            $price = $this->_parsePrice($priceText, 'por');

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}