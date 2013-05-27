<?php

namespace Bookscraper\Search;

class ResultBundle
{
    /**
     * Cheaper result.
     * Used for comparison purposes.
     *
     * @var \Bookscraper\Search\Result
     */
    protected $_cheaperResult;

    /**
     * Results bundle.
     *
     * @var array
     */
    protected $_results;

    /**
     * Search object.
     *
     * @var \Bookscraper\Search\Search
     */
    protected $_search;

    /**
     * Public constructor.
     *
     * @param \Bookscraper\Search\Search $search
     */
    public function __construct(\Bookscraper\Search\Search $search)
    {
        $this->_search = $search;
    }

    /**
     * Adds result to the bundle.
     *
     * @param  \Bookscraper\Search\Result $result
     * @return ResultBundle
     */
    public function addResult(\Bookscraper\Search\Result $result)
    {
        $this->_results[] = $result;

        if ($result->isCheaperThan($this->_cheaperResult)) {
            $this->_cheaperResult = $result;
        }

        return $this;
    }

    /**
     * Gets cheaper result.
     *
     * @return \Bookscraper\Search\Result
     */
    public function getCheaperResult()
    {
        return $this->_cheaperResult;
    }

    /**
     * Gets results bundle.
     *
     * @return array
     */
    public function getResults()
    {
        return $this->_results;
    }

    /**
     * Gets search object.
     *
     * @return \Bookscraper\Search\Search
     */
    public function getSearch()
    {
        return $this->_search;
    }
}