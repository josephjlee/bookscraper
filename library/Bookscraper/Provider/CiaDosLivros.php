<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

class CiaDosLivros extends ProviderAbstract
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item)
    {
        $format = 'http://www.ciadoslivros.com.br/pesquisa/?p=%s';
        $uri = sprintf($format, urlencode($item->getTitle()));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new Result();
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