<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\Variant;

class Product extends Resource
{
    protected $resourcePath = 'products';
    protected $resourceName = 'product';
    protected $resourceNamePlural = 'products';

    public function variants()
    {
        return $this->hasMany(Variant::class); 
    }
}
