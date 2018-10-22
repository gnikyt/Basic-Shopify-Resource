<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Relationships\HasMany;
use OhMyBrew\BasicShopifyResource\Relationships\IncludesMany;
use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\ProductImage;
use OhMyBrew\BasicShopifyResource\Models\ProductVariant;
use OhMyBrew\BasicShopifyResource\Models\CustomCollection;
use OhMyBrew\BasicShopifyResource\Models\Collect;

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
            'variants'    => new IncludesMany(ProductVariant::class),
            'images'      => (new IncludesMany(ProductImage::class))->setParams(
                /**
                 * @codeCoverageIgnore
                 */
                function () {
                    return ['product_id' => $this->id];
                }
            ),
            'collections' => (new HasMany(CustomCollection::class))->setParams(
                /**
                 * @codeCoverageIgnore
                 */
                function () {
                    return ['product_id' => $this->id];
                }
            ),
            'collects'    => (new HasMany(Collect::class))->setParams(
                /**
                 * @codeCoverageIgnore
                 */
                function () {
                    return ['product_id' => $this->id];
                }
            ),
        ];
    }
}
