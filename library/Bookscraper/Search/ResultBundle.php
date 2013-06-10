<?php

namespace Bookscraper\Search;

use Bookscraper\Search\Result;

class ResultBundle
{
    /**
     * Cheaper result.
     * Used for comparison purposes.
     *
     * @var Result
     */
    protected $_cheaperResult;

    /**
     * Results bundle.
     *
     * @var array
     */
    protected $_results;

    /**
     * Adds result to the bundle.
     *
     * @param  Result $result
     * @return ResultBundle
     */
    public function addResult(Result $result)
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
     * @return Result
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
}