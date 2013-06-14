<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Search;

use Bookscraper\Search\Result;

/**
 * Result bundle class.
 *
 * Use result bundles to compare two or more search results, e.g.:
 *
 * <code>
 * use Bookscraper\Provider;
 * use Bookscraper\Search\CrawlerFactory;
 * use Bookscraper\Search\Item;
 * use Bookscraper\Search\ResultBundle;
 *
 * require_once 'vendor/autoload.php';
 *
 * $crawlerFactory = new CrawlerFactory();
 * $americanas = new Provider\Americanas();
 * $ciaDosLivros = new Provider\CiaDosLivros();
 * $item = new Item('Lolita', 'Vladimir Nabokov');
 * $resultBundle = new ResultBundle();
 *
 * $resultBundle->addResult($americanas->lookup($item, $crawlerFactory))
 *              ->addResult($ciaDosLivros->lookup($item, $crawlerFactory));
 *
 * $cheaperResult = $resultBundle->getCheaperResult();
 *
 * if ($cheaperResult->isNotEmpty()) {
 *     printf('Item found on %s for R$ %01.2f.',
 *     $cheaperResult->getUrl(), $cheaperResult->getPrice());
 * } else {
 *     echo 'Item not found.';
 * }
 * </code>
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class ResultBundle
{
    /**
     * Cheaper result.
     * Used for comparison purposes.
     *
     * @var Result
     */
    protected $cheaperResult;

    /**
     * Results bundle.
     *
     * @var array
     */
    protected $results;

    /**
     * Adds result to the bundle.
     *
     * @param  Result $result
     * @return ResultBundle
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;

        if ($result->isCheaperThan($this->cheaperResult)) {
            $this->cheaperResult = $result;
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
        return $this->cheaperResult;
    }

    /**
     * Gets results bundle.
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }
}
