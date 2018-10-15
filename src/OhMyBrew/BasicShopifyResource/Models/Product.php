<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;

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
            'variants'    => [self::INCLUDES_MANY, Variant::class],
            'images'      => [self::INCLUDES_MANY, Image::class],
            'collections' => [self::HAS_MANY, CustomCollection::class, function () {
                return ['product_id' => $this->id];
            }],
            'collects'    => [self::HAS_MANY, Collect::class, function () {
                return ['product_id' => $this->id];
            }],
        ];
    }
}
