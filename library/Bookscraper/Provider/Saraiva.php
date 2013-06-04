<?php

namespace Bookscraper\Provider;

class Saraiva extends ProviderAbstract
{
    /**
     * Gets provider name.
     *
     * @see    ProviderAbstract::getName()
     * @return string
     */
    public function getName()
    {
        return 'Saraiva.com.br';
    }

    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://busca.livrariasaraiva.com.br/'
                . 'search?w=%s&af=cat1%%3alivros';

        $uri = sprintf($format, urlencode($search->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result($this);
        $errorMessage = 'N&atilde;o foram encontrados resultados para '
                      . 'todas as palavras da sua pesquisa.';

        if (strpos($content, $errorMessage) === false) {
            $url = $crawler->filter('.sli_grid_result > a')->link()->getUri();
            $priceText = $crawler->filter('.precoPor')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}