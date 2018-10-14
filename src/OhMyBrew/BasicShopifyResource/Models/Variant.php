<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\Product;

class Variant extends Resource
{
    /**
     * The resource path part.
     *
     * @var string
     */
    protected $resourcePath = 'variants';

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = 'variant';

    /**
     * The resource name (plural).
     *
     * @var string
     */
    protected $resourceNamePlural = 'variant';

    /**
     * Has one: product
     *
     * @return Product
     */
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
