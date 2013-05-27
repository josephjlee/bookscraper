<?php

ini_set('display_errors', true);

require_once 'vendor/autoload.php';

$titles = array(
    array('A Ausência que Seremos', 'Héctor Abad'),
    'A Cauda Longa',
    array('A Cidade e os Cachorros', 'Mario Vargas Llosa'),
    array('A Divina Comédia dos Mutantes', 'Carlos Calado'),
    array('A Faca de Dois Gumes', 'Fernando Sabino'),
    array('A Fúria dos Reis: As Crônicas de Gelo e Fogo: Livro Dois', 'George R.R. Martin'),
    array('A Guerra dos Tronos: As Crônicas de Gelo e Fogo - Livro Um', 'George R.R. Martin'),
    array('A Ilha do Dia Anterior', 'Umberto Eco'),
    array('A Noite das Mulheres Cantoras', 'Lídia Jorge'),
    array('A Pianista', ' Elfriede Jelinek'),
);

$providers = array(
    new \Bookscraper\Provider\Submarino(),
    new \Bookscraper\Provider\Americanas(),
    new \Bookscraper\Provider\CiaDosLivros(),
    new \Bookscraper\Provider\Fnac(),
);

$searchBundle = new \Bookscraper\Search\SearchBundle($titles, $providers);
$resultBundles = $searchBundle->lookup();

foreach ($resultBundles as $resultBundle) {
    /* @var $resultBundle \Bookscraper\Search\ResultBundle */
    $title = $resultBundle->getSearch()->getTitle();
    $cheaperResult = $resultBundle->getCheaperResult();

    if ($cheaperResult === null) {
        printf('"%s" can not be found on any search provider.', $title);
    } else {
        printf('"%s" can be found on %s (%s) for R$ %01.2f.',
            $title, $cheaperResult->getProvider()->getName(),
            $cheaperResult->getUrl(), $cheaperResult->getPrice());
    }

    echo PHP_EOL;
}