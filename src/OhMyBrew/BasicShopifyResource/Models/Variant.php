<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Relationships\HasOne;

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
            /* Needs hasOneThrough .. 'image'   => [self::HAS_ONE, Image::class, function () {
                return ['id' => $this->image_id];
            }],*/
        ];
    }
}
