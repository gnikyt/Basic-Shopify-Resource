<?php

namespace OhMyBrew\BasicShopifyResource\Relationships;

use Closure;

/**
 * Base relationship class.
 */
abstract class Relationship
{
    /**
     * The resource class for the relationship.
     *
     * @var string
     */
    protected $resource;

    /**
     * Additional parameters for the relationship.
     *
     * @var Closure
     */
    protected $params;

    /**
     * Sets the resource for the relationship.
     *
     * @param string $resource The resource class for the relationship.
     *
     * @return $this
     */
    public function __construct(string $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Gets the resource for the relationship.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets the additional parameters to call.
     *
     * @param Closure $paramsCallable The callable params closure.
     *
     * @return $this
     */
    public function setParams(Closure $paramsCallable)
    {
        $this->params = $paramsCallable;

        return $this;
    }

    /**
     * Gets the additional parameters for the relationship.
     *
     * @return array
     */
    public function getParams()
    {
        return is_callable($this->params) ? call_user_func($this->params) : [];
    }
}
