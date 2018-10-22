<?php

namespace OhMyBrew\BasicShopifyResource\Relationships;

use Closure;

class HasManyThrough extends Relationship
{
    /**
     * The through resource.
     *
     * @var string
     */
    protected $through;

    /**
     * Sets the through resource.
     *
     * @param Closure $through The through resource.
     *
     * @return $this
     */
    public function setThrough(Closure $through)
    {
        $this->through = $through;

        return $this;
    }

    /**
     * Gets the through resource class.
     *
     * @return Closure
     */
    public function getThrough()
    {
        return $this->through;
    }
}
