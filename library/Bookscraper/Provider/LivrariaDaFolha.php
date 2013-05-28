<?php

namespace Bookscraper\Provider;

class LivrariaDaFolha extends ProviderAbstract
{
    /**
     * Gets provider name.
     *
     * @see    ProviderAbstract::getName()
     * @return string
     */
    public function getName()
    {
        return 'Livraria da Folha';
    }

    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://livraria.folha.com.br/busca?q=%s';
        $title = utf8_decode($search->getTitle());
        $uri = sprintf($format, urlencode($title));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result();
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