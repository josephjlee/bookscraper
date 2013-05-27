<?php

namespace Bookscraper\Provider;

class Submarino extends ProviderAbstract
{
    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://busca.submarino.com.br/busca.php?q=%s&cat=460';
        $uri = sprintf($format, urlencode($search->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result();
        $errorMessage = 'Desculpe, no momento não temos  esse produto';

        if (strpos($content, $errorMessage) === false) {
            $url = $crawler->filter('.list .url')->link()->getUri();
            $crawler = $this->_createCrawler($url, $content);
            $priceText = $crawler->filter('strong .amount')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}