<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Relationships\HasOne;
use OhMyBrew\BasicShopifyResource\Relationships\HasOneThrough;
use OhMyBrew\BasicShopifyResource\Models\Image;
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
    protected $resourceNamePlural = 'variants';

    /**
     * The constructor.
     *
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'product' => (new HasOne(Product::class))->setParams(function () {
                return ['product_id' => $this->product_id];
            }),
            'image'   => (new HasOneThrough(Image::class))
                ->setThrough(Product::class)
                ->setThroughParams(function () {
                    return $this->product_id;
                })
        ];
    }
}
