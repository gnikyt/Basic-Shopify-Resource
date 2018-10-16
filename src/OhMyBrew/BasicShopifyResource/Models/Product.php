<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Relationships\IncludesMany;
use OhMyBrew\BasicShopifyResource\Relationships\HasMany;

/**
 * Product API.
 */
class Product extends Resource
{
    /**
     * The resource path part.
     *
     * @var string
     */
    protected $resourcePath = 'products';

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = 'product';

    /**
     * The resource name (plural).
     *
     * @var string
     */
    protected $resourceNamePlural = 'products';

    /**
     * The constructor.
     *
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'variants'    => new IncludesMany(Variant::class),
            'images'      => (new IncludesMany(Image::class))->setParams(function () {
                return ['product_id' => $this->id];
            }),
            'collections' => (new HasMany(CustomCollection::class))->setParams(function () {
                return ['product_id' => $this->id];
            }),
            'collects'    => (new HasMany(Collect::class))->setParams(function () {
                return ['product_id' => $this->id];
            }),
        ];
    }
}
