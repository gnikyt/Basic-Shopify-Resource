<?php

namespace OhMyBrew\BasicShopifyResource;

use Tightenco\Collect\Support\Collection;

abstract class Resource
{
    protected $properties = [];
    protected $mutatedProperties = [];
    protected $resourcePath = null;
    protected $resourceName = null;
    protected $resourceNamePlural = null;

    protected static function request(string $type, $resourceId = null, array $params = [], $through = null)
    {
        $instance = self::getInstance();
        $resourceName = $instance->resourceName;
        $resourceNamePlural = $instance->resourceNamePlural;
        $resourcePath = $instance->resourcePath;

        $path = ['/admin'];
        if ($through) {
            $path[] = $through->resourcePath;
            $path[] = $through->id;
        }
        $path[] = $resourcePath;

        if ($resourceId) {
            $path[] = $resourceId;
        }

        $response = self::getConnection()
            ->rest($type, implode('/', $path).'.json', $params)
            ->body
            ->{$resourceId ? $resourceName : $resourceNamePlural}
        ;

        if ($resourceId) {
            return self::buildResource($instance, $response);
        }

        $collection = new Collection();
        foreach ($response as $object) {
            $collection[] = self::buildResource($instance, $object);
        }

        return $collection;
    }

    protected static function buildResource($resource, $response)
    {
        foreach (get_object_vars($response) as $key => $value) {
            $resource->properties[$key] = $value;
        }

        return $resource;
    }

    public static function all(array $params = [])
    {
        return self::request('GET');
    }

    public static function allThrough($resource, array $params = [])
    {
        return self::request('GET', null, $params, $resource);
    }

    public static function find($id, array $params = [])
    {
        return self::request('GET', $id, $params);
    }

    public static function findThrough($id, $resource, array $params = [])
    {
        return self::request('GET', $id, $params, $resource);
    }

    public function hasOne($model, array $params = [], $key = null)
    {
        $instance = new $model;
        $instanceKey = $key ?? "{$instance->resourceName}_id";

        return $instance::find($this->{$instanceKey}, $params);
    }

    public function hasMany($model, array $params = [])
    {
        $instance = new $model;
        return $instance::allThrough($this, $params);
    }

    public function save()
    {
        $type = $this->id ? 'PUT' : 'POST';
        $newResource = self::request($type, $this->id ?? null, [
            $this->resourceName => $this->mutatedProperties
        ]);

        $this->refreshProperties($newResource->properties);
        $this->resetProperties();
    }

    public function destroy()
    {

    }

    protected static function getConnection()
    {
        return Connection::get();
    }

    protected static function getInstance($args = null)
    {
        $class = get_called_class();

        return new $class($args);
    }

    public function __get($property)
    {
        if( array_key_exists($property, $this->mutatedProperties) ){
            return $this->mutatedProperties[$property];
        }
        elseif( array_key_exists($property, $this->properties) ){
            return $this->properties[$property];
        }
        return null;
    }

    public function __set($property, $value)
    {
        $this->mutatedProperties[$property] = $value;
    }

    protected function refreshProperties($properties)
    {
        $this->properties = $properties;
    }

    public function resetProperties()
    {
        $this->modifiedProperties = [];
    }

    public function originalProperty($key)
    {
        return $this->properties[$key];
    }
}
