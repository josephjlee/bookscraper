<?php

namespace Bookscraper\Provider;

class LivrariaCultura implements ProviderInterface
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
        // First we need get the OAuth token.
        $title = $search->getTitle();
        $baseUri = 'http://www.livrariacultura.com.br';
        $spyUri = $baseUri . '/Produto/Busca?Buscar=' . urlencode($title);
        $headers = \Dz\Http\Client::getData($spyUri, array(
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
        ));

        $matches = array();

        preg_match('/LivrariaCulturaOauth=[^;]+/', $headers, $matches);

        // Then we can lookup.
        $uri = $baseUri . '/Service/Metodos.asmx/InvocarSimples';
        $headers = array('Cookie: ' . array_pop($matches));
        $nomeDaClasse = 'LivrariaCultura.Repository.Ecommerce.Buscador,'
                      . 'LivrariaCultura.Repository.Ecommerce';

        $parametrosDaEntidade = sprintf(
            '{"Expressao":"%s","Ordem":"RD"}', $title);

        $postFields = http_build_query(array(
            'NomeDaClasse'         => base64_encode($nomeDaClasse),
            'NomeDoMetodo'         => base64_encode('Obter'),
            'ParametrosDaEntidade' => $parametrosDaEntidade,
        ));

        $extraOptions = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postFields,
        );

        $data = \Dz\Http\Client::getData($uri, $extraOptions);
        $jsonData = preg_replace('/^[^\{]+(\{.*\}).*$/', '$1', $data);
        $json = json_decode($jsonData);
        $result = new \Bookscraper\Search\Result($this);

        if ($json->Result !== null && count($json->Result->Resultados) > 0) {
            $resultado = array_shift($json->Result->Resultados);

            $result->setPrice($resultado->Valor)
                   ->setUrl($baseUri . $resultado->UrlResenha);
        }

        return $result;
    }
}