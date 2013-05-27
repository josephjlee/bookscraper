<?php

namespace Bookscraper\Provider;

interface ProviderInterface
{
    /**
     * @param  \Bookscraper\Search\Search $search
     * @return \Bookscraper\Search\Result
     */
    public function lookup(\Bookscraper\Search\Search $search);
}