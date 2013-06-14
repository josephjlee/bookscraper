<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Search;

/**
 * Search item class.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Item
{
    /**
     * Author name.
     *
     * @var string
     */
    protected $author;

    /**
     * Book title.
     *
     * @var string
     */
    protected $title;

    /**
     * Public constructor.
     *
     * @param string $title
     * @param string $author
     */
    public function __construct($title, $author = null)
    {
        $this->title = $title;
        $this->author = $author;
    }

    /**
     * Gets cache driver.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Gets book title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets author name.
     *
     * @param  string $author
     * @return Item
     */
    public function setAuthor($author)
    {
        $this->author = $author;

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
        $this->title = $title;

        return $this;
    }
}
