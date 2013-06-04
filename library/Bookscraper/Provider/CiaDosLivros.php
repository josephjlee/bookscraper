<?php

namespace Bookscraper\Provider;

class CiaDosLivros extends ProviderAbstract
{
    /**
     * Gets provider name.
     *
     * @see    ProviderAbstract::getName()
     * @return string
     */
    public function getName()
    {
        return 'Cia dos Livros';
    }

    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://www.ciadoslivros.com.br/pesquisa/?p=%s';
        $uri = sprintf($format, urlencode($search->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result($this);
        $errorMessages = array(
            '&atilde;o encontrou resultados',
            '<span class="availability" style="display:none">out_of_stock</span>',
        );

        foreach ($errorMessages as $errorMessage) {
            if (strpos($content, $errorMessage) !== false) {
                return $result;
            }
        }

        $url = $crawler->filter('link[rel=canonical]')->attr('href');

        // No redirection?
        if ($url === $uri) {
            $url = $crawler->filter('h3 a')->link()->getUri();
        }

        $priceText = $crawler->filter('.sale')->text();
        $price = $this->_parsePrice($priceText);

        $result->setPrice($price)
               ->setUrl($url);

        return $result;
    }
}