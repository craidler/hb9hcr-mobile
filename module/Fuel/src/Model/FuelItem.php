<?php
namespace Fuel\Model;

use HB9HCR\Base\Item;

/**
 * Class Fuel
 * @package Fuel\Model
 */
class FuelItem extends Item
{
    protected static function filter(array $data): array
    {
        return array_filter($data, function ($k) {
            return in_array($k, ['id', 'date', 'odometer', 'volume', 'amount', 'currency']);
        }, ARRAY_FILTER_USE_KEY);
    }
}