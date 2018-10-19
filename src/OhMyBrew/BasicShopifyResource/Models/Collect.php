<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Relationships\HasOne;
use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\CustomCollection;

/**
 * Custom Collection API.
 */
class Collect extends Resource
{
    /**
     * The resource path part.
     *
     * @var string
     */
    protected $resourcePath = 'collects';

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = 'collect';

    /**
     * The resource name (plural).
     *
     * @var string
     */
    protected $resourceNamePlural = 'collects';

    /**
     * The constructor.
     *
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'product'    => new HasOne(Product::class),
            'collection' => new HasOne(CustomCollection::class),
        ];
    }
}
