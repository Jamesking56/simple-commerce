<?php

namespace DuncanMcClean\SimpleCommerce\Exceptions;

class ShippingMethodDoesNotExist extends \Exception
{
    public function __construct(string $shippingMethod)
    {
        parent::__construct("ShippingMethod method [{$shippingMethod}] does not exist.");
    }
}