<?php

namespace OhMyBrew\BasicShopifyResource\Relationships;

use Closure;

class HasOneThrough extends Relationship
{
    /**
     * The through resource class.
     *
     * @var string
     */
    protected $through;

    /**
     * The through resource ID.
     *
     * @var Closure
     */
    protected $throughId;

    /**
     * Sets the through resource.
     *
     * @param string $through The through resource class.
     *
     * @return $this
     */
    public function setThrough($through)
    {
        $this->through = $through;

        return $this;
    }

    /**
     * Gets the through resource class.
     *
     * @return string
     */
    public function getThrough()
    {
        return $this->through;
    }

    /**
     * Sets the through resource ID.
     *
     * @param Closure $idCallable The callable closure.
     *
     * @return $this
     */
    public function setThroughId(Closure $idCallable)
    {
        $this->throughId = $idCallable;

        return $this;
    }

    /**
     * Gets the thorugh resource ID.
     *
     * @return int
     */
    public function getThroughId()
    {
        return call_user_func($this->throughId);
    }
}
