<?php

namespace Bookscraper\Search;

class Search
{
    /**
     * Author name.
     *
     * @var string
     */
    protected $_author;

    /**
     * Book title.
     *
     * @var string
     */
    protected $_title;

    /**
     * @param string $title
     * @param string $author

     */
    public function __construct($title, $author = null)
    {
        $this->_title = $title;
        $this->_author = $author;
    }

    /**
     * Gets author name.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * Gets book title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param  array $providers
     * @return \Bookscraper\Search\ResultBundle
     */
    public function lookup(array $providers)
    {
        $resultBundle = new \Bookscraper\Search\ResultBundle($this);

        foreach ($providers as $provider) {
            /* @var $provider \Bookscraper\Provider\ProviderInterface */
            $result = $provider->lookup($this);

            $resultBundle->addResult($result);
        }

        return $resultBundle;
    }

    /**
     * Sets author name.
     *
     * @param  string $author
     * @return Search
     */
    public function setAuthor($author)
    {
        $this->_author = $author;

        return $this;
    }

    /**
     * Sets book title.
     *
     * @param  string $title
     * @return Search
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }
}