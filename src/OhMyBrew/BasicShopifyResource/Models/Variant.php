<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\Product;

class Variant extends Resource
{
    protected $resourcePath = 'variants';
    protected $resourceName = 'variant';
    protected $resourceNamePlural = 'variants';

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
