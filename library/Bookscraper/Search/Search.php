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
     * @var array
     */
    protected $_providers = array();

    /**
     * Book title.
     *
     * @var string
     */
    protected $_title;

    /**
     * @param string $title
     * @param string $author
     * @param array $providers
     */
    public function __construct($title, $author = null, array $providers)
    {
        $this->_title = $title;
        $this->_author = $author;
        $this->_providers = $providers;
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
     * @param  $providers
     * @return \Bookscraper\Search\ResultBundle
     */
    public function lookup()
    {
        $resultBundle = new \Bookscraper\Search\ResultBundle($this);

        foreach ($this->_providers as $provider) {
            /* @var $provider \Bookscraper\Provider\ProviderInterface */
            $result = $provider->lookup($this)
                               ->setProvider($provider);

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