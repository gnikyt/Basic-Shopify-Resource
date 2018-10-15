<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Models\Collect;

/**
 * Custom Collection API
 */
class CustomCollection extends Resource
{
    /**
     * The resource path part.
     *
     * @var string
     */
    protected $resourcePath = 'custom_collections';

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = 'custom_collection';

    /**
     * The resource name (plural).
     *
     * @var string
     */
    protected $resourceNamePlural = 'custom_collections';

    /**
     * The constructor.
     * 
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'collects' => [self::HAS_MANY, Collect::class, function () { return ['collection_id' => $this->id]; }],
        ];
    }
}
