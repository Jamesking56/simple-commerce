<?php

namespace DuncanMcClean\SimpleCommerce\Facades;

use DuncanMcClean\SimpleCommerce\Shipping\ShippingMethodRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see \DuncanMcClean\SimpleCommerce\Shipping\ShippingMethodRepository
 */
class ShippingMethod extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ShippingMethodRepository::class;
    }
}