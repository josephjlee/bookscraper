<?php

namespace Bookscraper\Provider;

class Americanas extends ProviderAbstract
{
    /**
     * Gets provider name.
     *
     * @see    ProviderAbstract::getName()
     * @return string
     */
    public function getName()
    {
        return 'Americanas.com';
    }

    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://busca.americanas.com.br/busca.php?q=%s&cat=9';
        $uri = sprintf($format, urlencode($search->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result($this);
        $errorMessages = array(
            'Nenhum resultado encontrado para sua consulta',
            'não retornou nenhum resultado',
        );

        foreach ($errorMessages as $errorMessage) {
            if (strpos($content, $errorMessage) !== false) {
                return $result;
            }
        }

        $url = $crawler->filter('.list .url')->link()->getUri();
        $crawler = $this->_createCrawler($url, $content);
        $priceText = $crawler->filter('strong .price')->text();
        $price = $this->_parsePrice($priceText);

        $result->setPrice($price)
               ->setUrl($url);

        return $result;
    }
}