<?php

namespace OhMyBrew\BasicShopifyResource\Models;

use OhMyBrew\BasicShopifyResource\Relationships\HasManyThrough;
use OhMyBrew\BasicShopifyResource\Resource;

/**
 * Theme API.
 */
class Theme extends Resource
{
    /**
     * The resource path part.
     *
     * @var string
     */
    protected $resourcePath = 'themes';

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = 'theme';

    /**
     * The resource name (plural).
     *
     * @var string
     */
    protected $resourceNamePlural = 'themes';

    /**
     * The constructor.
     *
     * @return $this
     */
    public function __construct()
    {
        $this->relationships = [
            'assets'   => (new HasManyThrough(Asset::class))
                /*
                 * @codeCoverageIgnore
                 */
                ->setThrough(function () {
                    return $this;
                }),
        ];
    }
}
