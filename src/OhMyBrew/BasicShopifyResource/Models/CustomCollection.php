<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Relationships\HasMany;

/**
 * Custom Collection API.
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
            'collects' => (new HasMany(Collect::class))->setParams(
                /**
                 * @codeCoverageIgnore
                 */
                function () {
                    return ['collection_id' => $this->id];
                }
            ),
        ];
    }
}
