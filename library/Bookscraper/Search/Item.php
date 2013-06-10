<?php

namespace Bookscraper\Search;

class Item
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
     * Gets cache driver.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * Gets item identifier key for caching purposes.
     *
     * @param  string $suffix
     * @return string
     */
    public function getKey($suffix = '')
    {
        return base64_encode($this->_title . $this->_author . $suffix);
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
     * Sets author name.
     *
     * @param  string $author
     * @return Item
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
     * @return Item
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }
}