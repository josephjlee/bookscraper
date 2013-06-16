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

``` php
<?php

use Bookscraper\Provider;
use Bookscraper\Search\CrawlerFactory;
use Bookscraper\Search\Item;

require_once 'vendor/autoload.php';

$crawlerFactory = new CrawlerFactory();
$provider = new Provider\Americanas();
$item = new Item('Lolita', 'Vladimir Nabokov');
$result = $provider->lookup($item, $crawlerFactory);

if ($result->isNotEmpty()) {
    printf('Item found on %s for R$ %01.2f.',
        $result->getUrl(), $result->getPrice());
} else {
    echo 'Item not found.';
}
```

To increase performance, you can attach a cache driver to the `CrawlerFactory` HTTP client:

``` php

$cacheDriver = new \Dz\Cache\Driver\File('/path/to/cache/files');

$crawlerFactory->getHttpClient()
               ->setCacheDriver($cacheDriver);
```
