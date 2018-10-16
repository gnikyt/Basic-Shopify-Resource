<?php

namespace OhMyBrew\BasicShopifyResource\Relationships;

use OhMyBrew\BasicShopifyResource\Relationships\Relationship;

class IncludesOne extends Relationship
{
    /**
     * Forign key to the resource.
     *
     * @var string|null
     */
    protected $forignKey = null;

    /**
     * Sets the forgin key for the resource.
     *
     * @param string $key The forign key.
     *
     * @return $this
     */
    public function setForignKey(string $key)
    {
        $this->forignKey = $key;

        return $this;
    }

    /**
     * Gets the forign key.
     *
     * @return string
     */
    public function getForignKey()
    {
        return $this->forignKey;
    }
}
