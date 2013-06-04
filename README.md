Bookscraper, PHP web scraper to find cheap books. 
=================================================

Bookscraper is a little PHP experiment to grab cheap books by searching some brazilian bookstores. It uses [Composer](http://getcomposer.org/), Symfony [DomCrawler](https://github.com/symfony/DomCrawler) and [CssSelector](https://github.com/symfony/CssSelector) components and [DZ Framework](https://github.com/dzestudio/dz-framework) HTTP client and allows the use of custom search providers.

Installation
------------

Download the [`composer.phar`](https://getcomposer.org/composer.phar) executable or use the installer.

``` sh
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

Usage
-----

There is three basic ways to search books with Bookscraper.

### Single title, single provider

``` php
<?php

require_once 'vendor/autoload.php';

$title = 'Lolita';
$author = 'Vladimir Nabokov';
$search = new \Bookscraper\Search\Search($title, $author);
$provider = new \Bookscraper\Provider\LivrariaCultura();
$result = $provider->lookup($search);

if ($result === null) {
    printf('"%s" could not be found on provider.', $title);
} else {
    printf('"%s" can be found on %s (%s) for R$ %01.2f.',
    $title, $result->getProvider()->getName(),
    $result->getUrl(), $result->getPrice());
}
```

### Single title, multiple providers

``` php
<?php

require_once 'vendor/autoload.php';

$providers = array(
    new \Bookscraper\Provider\Americanas(),
    new \Bookscraper\Provider\CiaDosLivros(),
    new \Bookscraper\Provider\Fnac(),
    new \Bookscraper\Provider\LivrariaCultura(),
    new \Bookscraper\Provider\LivrariaDaFolha(),
    new \Bookscraper\Provider\Lpm(),
    new \Bookscraper\Provider\Saraiva(),
    new \Bookscraper\Provider\Submarino(),
);

$title = 'A Ilha do Dia Anterior';
$author = 'Umberto Eco';
$search = new \Bookscraper\Search\Search($title, $author);
$resultBundle = $search->lookup($providers);

if (($cheaperResult = $resultBundle->getCheaperResult()) === null) {
    printf('"%s" could not be found on any search provider.', $title);
} else {
    printf('"%s" can be found on %s (%s) for R$ %01.2f.',
    $title, $cheaperResult->getProvider()->getName(),
    $cheaperResult->getUrl(), $cheaperResult->getPrice());
}
```

### Multiple titles, multiple providers

``` php
<?php

require_once 'vendor/autoload.php';

$titles = array(
    array('Uma História Íntima da Humanidade', 'Theodore Zeldin'), // Title/author pair
    array('Não me Faça Pensar'), // Just title
    'As Brasas', // Just title as string
);

$providers = array(
    new \Bookscraper\Provider\Americanas(),
    new \Bookscraper\Provider\CiaDosLivros(),
    new \Bookscraper\Provider\Fnac(),
    new \Bookscraper\Provider\LivrariaCultura(),
    new \Bookscraper\Provider\LivrariaDaFolha(),
    new \Bookscraper\Provider\Lpm(),
    new \Bookscraper\Provider\Saraiva(),
    new \Bookscraper\Provider\Submarino(),
);

$searchBundle = new \Bookscraper\Search\SearchBundle($titles, $providers);
$resultBundles = $searchBundle->lookup();

foreach ($resultBundles as $resultBundle) {
    /* @var $resultBundle \Bookscraper\Search\ResultBundle */
    $title = $resultBundle->getSearch()->getTitle();

    if (($cheaperResult = $resultBundle->getCheaperResult()) === null) {
        printf('"%s" could not be found on any search provider.', $title);
    } else {
        printf('"%s" can be found on %s (%s) for R$ %01.2f.',
        $title, $cheaperResult->getProvider()->getName(),
        $cheaperResult->getUrl(), $cheaperResult->getPrice());
    }

    echo PHP_EOL;
}
```