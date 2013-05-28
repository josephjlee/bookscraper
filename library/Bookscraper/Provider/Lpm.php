<?php

namespace Bookscraper\Provider;

class Lpm extends ProviderAbstract
{
    /**
     * Gets provider name.
     *
     * @see    ProviderAbstract::getName()
     * @return string
     */
    public function getName()
    {
        return 'L&PM';
    }

    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://www.lpm.com.br/livros/layout_buscaprodutos.asp'
                . '?Tipo=Livros&FiltroTitulo=%s&FiltroAutor=%s';

        $title = utf8_decode($search->getTitle());
        $author = utf8_decode($search->getAuthor());
        $uri = sprintf($format, urlencode($title), urlencode($author));
        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result();
        $errorMessage = 'Nenhum livro foi encontrado';

        if (strpos($content, $errorMessage) === false) {
            $url = $crawler->filter('h2 a')->link()->getUri();
            $priceText = $crawler->filter('.p2 b')->text();
            $price = $this->_parsePrice($priceText);

            $result->setPrice($price)
                   ->setUrl($url);
        }

        return $result;
    }
}