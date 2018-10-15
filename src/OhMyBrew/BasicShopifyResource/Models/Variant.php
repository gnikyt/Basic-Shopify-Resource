<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\Image;

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
    protected $resourceNamePlural = 'variants';

    /**
     * The constructor.
     * 
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'product' => [self::HAS_ONE, Product::class, function () { return ['product_id' => $this->product_id]; }],
            'image'   => [self::HAS_ONE, Image::class],
        ];
    }
}
