<?php

namespace Bookscraper\Provider;

use Bookscraper\Search\Item;
use Bookscraper\Search\Result;

interface ProviderInterface
{
    /**
     * @param  Item $item
     * @return Result
     */
    public function lookup(Item $item);
}