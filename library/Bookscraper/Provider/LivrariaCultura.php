<?php

namespace Bookscraper\Provider;

class LivrariaCultura extends ProviderAbstract
{
    /**
     * Gets provider name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Livraria Cultura';
    }

    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search)
    {
        $format = 'http://www.livrariacultura.com.br/scripts/busca/busca.asp'
                . '?avancada=1&titem=1&palavratitulo=%s&modobuscatitulo=pc'
                . '&palavraautor=%s&modobuscaautor=pc'
                . '&cidioma=POR&ordem=disponibilidade';

        $uri = sprintf($format, urlencode($search->getTitle()),
            urlencode($search->getAuthor()));

        $content = '';
        $crawler = $this->_createCrawler($uri, $content);
        $result = new \Bookscraper\Search\Result($this);
        $errorMessage = 'nenhum resultado correspondente';

        if (strpos($content, $errorMessage) === false) {
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