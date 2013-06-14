<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Provider;

/**
 * Provider base class.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 * @todo      Remove this one ASAP.
 */
abstract class ProviderAbstract implements ProviderInterface
{
    /**
     * Parses price text to float value.
     *
     * @param  string $priceText
     * @return float
     */
    protected function parsePrice($priceText)
    {
        $price = preg_replace('/^\D*(\d+)[\.,](\d+)\D*$/', '$1.$2', $priceText);

        return (float) $price;
    }
}
