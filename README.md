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

To search a single title, use `Bookscraper\Search\Search`:

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

$title = 'A Ausência que Seremos';
$author = 'Héctor Abad';
$search = new \Bookscraper\Search\Search($title, $author, $providers);
$resultBundle = $search->lookup();

if (($cheaperResult = $resultBundle->getCheaperResult()) === null) {
    printf('"%s" can not be found on any search provider.', $title);
} else {
    printf('"%s" can be found on %s (%s) for R$ %01.2f.',
        $title, $cheaperResult->getProvider()->getName(),
        $cheaperResult->getUrl(), $cheaperResult->getPrice());
}
```

To search several titles all at once, use `Bookscraper\Search\SearchBundle`:

``` php
<?php

require_once 'vendor/autoload.php';

$titles = array(
    array('A Ausência que Seremos', 'Héctor Abad'), // title/author pair
    'A Cauda Longa', // just title
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
        printf('"%s" can not be found on any search provider.', $title);
    } else {
        printf('"%s" can be found on %s (%s) for R$ %01.2f.',
            $title, $cheaperResult->getProvider()->getName(),
            $cheaperResult->getUrl(), $cheaperResult->getPrice());
    }

    echo PHP_EOL;
}
```