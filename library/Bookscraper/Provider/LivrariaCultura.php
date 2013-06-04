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
        $baseUri = 'http://www.livrariacultura.com.br';
        $uri = $baseUri . '/Service/Metodos.asmx/InvocarSimples';
        $headers = array(
            'Cookie: LivrariaCulturaOauth=26bed376-8a46-4f3e-b2bb-ba9e65cb7843',
        );

        $nomeDaClasse = 'LivrariaCultura.Repository.Ecommerce.Buscador,'
                      . 'LivrariaCultura.Repository.Ecommerce';

        $parametrosDaEntidade = sprintf('{"Expressao":"%s","Ordem":"RD"}',
            $search->getTitle());

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
        $result = new \Bookscraper\Search\Result();

        if ($json->Result !== null && count($json->Result->Resultados) > 0) {
            $resultado = array_shift($json->Result->Resultados);

            $result->setPrice($resultado->Valor)
                   ->setUrl($baseUri . $resultado->UrlResenha);
        }

        return $result;
    }
}