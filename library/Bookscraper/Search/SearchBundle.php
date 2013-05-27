<?php

namespace Bookscraper\Search;

class SearchBundle
{
    /**
     * @var array
     */
    protected $_searchs = array();

    /**
     * @param array $titles
     * @param array $providers
     */
    public function __construct(array $titles, array $providers)
    {
        $this->_buildSearches($titles, $providers);
    }

    /**
     * @param array $titles
     * @param array $providers
     */
    protected function _buildSearches(array $titles, array $providers)
    {
        foreach ($titles as $title) {
            if (!is_array($title)) {
                $title = array($title);
            }

            $this->_searchs[] = new \Bookscraper\Search\Search(
                array_shift($title), array_shift($title), $providers);
        }
    }

    /**
     * @return array of \Bookscraper\Search\ResultBundle.
     */
    public function lookup()
    {
        $resultBundles = array();

        foreach ($this->_searchs as $search) {
            /* @var $search \Bookscraper\Search\Search */
            $resultBundles[] = $search->lookup();
        }

        return $resultBundles;
    }
}