<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\Product;

/**
 * Image API
 */
class Image extends Resource
{
    /**
     * The resource path part.
     *
     * @var string
     */
    protected $resourcePath = 'images';

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = 'image';

    /**
     * The resource name (plural).
     *
     * @var string
     */
    protected $resourceNamePlural = 'images';

    /**
     * The resource's relationships.
     *
     * @var array
     */
    protected $relationships = [
        'product' => [self::HAS_ONE, Product::class],
    ];
}
