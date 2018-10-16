<?php

namespace OhMyBrew\BasicShopifyResource\Relationships;

use OhMyBrew\BasicShopifyResource\Relationships\Relationship;

class HasOneThrough extends Relationship
{
    protected $through;

    protected $throughParams;

    public function setThrough($through)
    {
        $this->through = $through;

        return $this;
    }

    public function getThrough()
    {
        return $this->through;
    }

    public function setThroughParams($paramsCallable)
    {
        $this->throughParams = $paramsCallable;

        return $this;
    }

    /**
     * Gets the additional parameters for the relationship.
     *
     * @return array
     */
    public function getThroughParams()
    {
        return is_callable($this->throughParams) ? call_user_func($this->throughParams) : [];
    }
}
