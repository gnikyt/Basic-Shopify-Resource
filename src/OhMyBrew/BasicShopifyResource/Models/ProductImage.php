<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Relationships\HasOne;
use OhMyBrew\BasicShopifyResource\Resource;

/**
 * ProductImage API.
 */
class ProductImage extends Resource
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
     * The constructor.
     *
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'product' => new HasOne(Product::class),
        ];
    }
}
