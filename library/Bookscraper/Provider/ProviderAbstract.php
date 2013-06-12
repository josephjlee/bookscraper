<?php

namespace Bookscraper\Provider;

abstract class ProviderAbstract implements ProviderInterface
{
    /**
     * @param  string $priceText
     * @return float
     */
    protected function _parsePrice($priceText)
    {
        $price = preg_replace('/^\D*(\d+)[\.,](\d+)\D*$/', '$1.$2', $priceText);

        return (float) $price;
    }
}