<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Relationships\HasOne;
use OhMyBrew\BasicShopifyResource\Relationships\HasOneThrough;
use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\ProductImage;
use OhMyBrew\BasicShopifyResource\Models\Product;

/**
 * ProductVariant API.
 */
class ProductVariant extends Resource
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
            'product' => (new HasOne(Product::class))->setParams(
                /**
                 * @codeCoverageIgnore
                 */
                function () {
                    return ['product_id' => $this->product_id];
                }
            ),
            'image'   => (new HasOneThrough(ProductImage::class))
                ->setThrough(Product::class)
                ->setThroughId(
                    /**
                     * @codeCoverageIgnore
                     */
                    function () {
                        return $this->product_id;
                    }),
        ];
    }
}
