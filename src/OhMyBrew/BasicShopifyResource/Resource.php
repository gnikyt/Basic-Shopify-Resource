<?php

namespace OhMyBrew\BasicShopifyResource;

use Tightenco\Collect\Support\Collection;

/**
 * Resource class which all models are based on.
 */
abstract class Resource
{
    const HAS_ONE = 0;
    const HAS_MANY = 1;

    /**
     * The properties of the resource, such as ID, title, etc.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * The modified properties of the resource.
     *
     * @var array
     */
    protected $mutatedProperties = [];

    /**
     * The resource's path part.
     *
     * @var string
     */
    protected $resourcePath = null;

    /**
     * The resource's name, such as "product"
     *
     * @var string
     */
    protected $resourceName = null;

    /**
     * The resource's plural name, such as "products"
     *
     * @var string
     */
    protected $resourceNamePlural = null;

    /**
     * The resource's primary key.
     *
     * @var string
     */
    protected $resourcePk = null;

    /**
     * The resource's relationships.
     *
     * @var array
     */
    protected $relationships = null;

    /**
     * Gets the API instance.
     *
     * @return \OhMyBrew\BasicShopifyAPI
     */
    protected static function getConnection()
    {
        return Connection::get();
    }

    /**
     * Creates an instance of the calling static class.
     *
     * @return object
     */
    protected static function getInstance()
    {
        // Get the calling class
        $class = get_called_class();

        return new $class();
    }

    /**
     * Request handler which forms the request makes
     * a call through the API and parses the result into
     * either a model, or a collection of models.
     *
     * @param bool        $type       If this is a private or public app call.
     * @param int|null    $resourceId The ID of the resource to target.
     * @param array       $params     Additional parameters to pass with the request.
     * @param object|null $through    To form this request through another resource.
     *
     * @return Collection|object|null
     */
    protected static function request(string $type, $resourceId = null, array $params = [], $through = null)
    {
        // Create and get the current instance of this model
        $resource = self::getInstance();
        $resourceName = $resource->resourceName;
        $resourceNamePlural = $resource->resourceNamePlural;
        $resourcePath = $resource->resourcePath;

        // Form the request URL
        $path = ['/admin'];
        if ($through) {
            // If we're going through, form this part first
            $path[] = $through->resourcePath;
            $path[] = $through->{$through->getPk()};
        }
        $path[] = $resourcePath;

        if ($resourceId) {
            // Add the targeted resource ID
            $path[] = $resourceId;
        }

        // Create the request and get the response
        $path = implode('/', $path).'.json';
        $response = self::getConnection()
            ->rest($type, $path, $params)
            ->body
            ->{$resourceId ? $resourceName : $resourceNamePlural}
        ;

        if ($type !== 'DELETE') {
            if ($resourceId) {
                // If singular, build a single model
                return self::buildResource($resource, $response);
            }

            // Multiple, build many models
            return self::buildResourceCollection($resource, $response);
        }

        return null;
    }

    /**
     * Creates a model based on the response.
     *
     * @param object $resource The model.
     * @param object $data     The data for the model.
     *
     * @return object
     */
    protected static function buildResource($resource, $data)
    {
        if (!$resource instanceof Resource) {
            // Not yet initialized
            $resource = new $resource();
        }

        // Loop the public properties of the response, add them to the properties of the model
        foreach (get_object_vars($data) as $property => $value) {
            $resource->properties[$property] = $value;
        }

        return $resource;
    }

    /**
     * Creates a collection of models based on the response.
     *
     * @param object $resource The model.
     * @param object $data     The data for the model.
     *
     * @return Collection
     */
    protected static function buildResourceCollection($resource, $data)
    {
        $collection = new Collection();
        foreach ($data as $object) {
            $collection[] = self::buildResource($resource, $object);
        }

        return $collection;
    }

    /**
     * Finds all records of a resource.
     *
     * @param array $params Additional param to pass with the request.
     *
     * @return Collection
     */
    public static function all(array $params = [])
    {
        // Simple GET call
        return self::request('GET');
    }

    /**
     * Finds all records of a resource through another resource.
     *
     * @param array $params Additional param to pass with the request.
     *
     * @return Collection
     */
    public static function allThrough($resource, array $params = [])
    {
        // GET call with no resource ID, through a another resource
        return self::request('GET', null, $params, $resource);
    }

    /**
     * Finds a single record of a resource.
     *
     * @param int   $resourceId The ID of the resource.
     * @param array $params     Additional param to pass with the request.
     *
     * @return object
     */
    public static function find($resourceId, array $params = [])
    {
        // GET call with a resource ID
        return self::request('GET', $resourceId, $params);
    }

    /**
     * Finds a single record of a resource through another resource.
     *
     * @param int           $resourceId      The ID of the resource.
     * @param object|string $throughResource The resource to loop through
     * @param array         $params          Additional param to pass with the request.
     *
     * @return object
     */
    public static function findThrough($resourceId, $throughResource, array $params = [])
    {
        // GET call with a resource ID through a resource
        return self::request('GET', $resourceId, $params, $throughResource);
    }

    /**
     * Relationship of hasOne.
     *
     * @param string       $resource The class name of the resource.
     * @param array        $params   Additional param to pass with the request.
     * @param string|null  $key      The key to link.
     *
     * @return object
     */
    public function hasOne($resource, array $params = [], $key = null)
    {
        // Create an instance of the resource
        $instance = new $resource();
        if (!$key) {
            // If no key is entered, build one
            $key = "{$instance->resourceName}_id";
        }

        return $instance::find($this->{$key}, $params);
    }

    /**
     * Relationship of hasMany.
     *
     * @param string $resource The class name of the resource.
     * @param array  $params   Additional param to pass with the request.
     *
     * @return Collection
     */
    public function hasMany($resource, array $params = [])
    {
        $instance = new $resource();
        return $instance::allThrough($this, $params);
    }

    /**
     * Saves (or creates) a record.
     *
     * @return void
     */
    public function save()
    {
        $type = $this->isNew() ? 'POST' : 'PUT';
        $id = $this->isNew() ? null : $this->{$this->getPk()};
        $params = [$this->resourceName => $this->mutatedProperties];

        // Create the request to create or save the record, params will turn into
        // something like: ['product' => ['title' => 'New Title', ...]]
        $record = self::request($type, $id, $params);

        // Refresh the record's properties and clear out old mutations
        $this->refreshProperties($record->properties);
        $this->resetProperties();
    }

    /**
     * Destroys a record.
     *
     * @return void
     */
    public function destroy()
    {
        self::request('DELETE', $this->{$this->getPk()});
    }

    /**
     * Determines if this is a new record or not.
     *
     * @return boolean
     */
    public function isNew()
    {
        return !isset($this->properties[$this->getPk()]);
    }

    /**
     * Determines if this is an existing record or not.
     *
     * @return boolean
     */
    public function isExisting()
    {
        return !$this->isNew();
    }

    /**
     * Gets the primary key for the resource.
     *
     * @return string
     */
    public function getPk()
    {
        // Default to ID
        return $this->resourcePk ?? 'id';
    }

    /**
     * Checks if a property of a record is defined as a relationship.
     *
     * @param string $property The property to search.
     *
     * @return boolean
     */
    protected function isRelationalProperty($property)
    {
        return isset($this->relationships[$property]);
    }

    /**
     * Magic getter to ensure we can only grab the record's properties.
     *
     * @param string $property The property name.
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->isRelationalProperty($property)) {
            // Its a relationship property, see if we've already binded
            if (isset($this->properties[$property]) && ($this->properties[$property] instanceof Resource || $this->properties[$property] instanceof Collection)) {
                // Already binded, simply return the result
                return $this->properties[$property];
            }

            // Get the relationship; 0 = type, 1 = class, 2 = params, 3 = linking key
            $relationship = $this->relationships[$property];
            $type = $relationship[0];
            $class = $relationship[1];
            $params = $relationship[2] ?? [];
            $linking = $relationship[3] ?? null;

            if ($type === self::HAS_MANY) {
                // Has many
                if (isset($this->properties[$property])) {
                    // Data is present from initial resource call, simply bind it to the model
                    $this->properties[$property] = self::buildResourceCollection($class, $this->properties[$property]);
                } else {
                    // No data is present, make an API call
                    $this->properties[$property] = $this->hasMany($class, $params);
                }
            } elseif ($type === self::HAS_ONE) {
                // Has one
                if (isset($this->properties[$property])) {
                    // Data is present from initial resource call, simply bind it to the model
                    $this->properties[$property] = self::buildResource($class, $this->properties[$property]);
                } else {
                    // No data is present, make an API call
                    $this->properties[$property] = $this->hasOne($class, $params, $linking);
                }
            }

            return $this->properties[$property];
        } elseif (array_key_exists($property, $this->mutatedProperties)) {
            // Its mutated, get the mutated property version
            return $this->mutatedProperties[$property];
        } elseif (array_key_exists($property, $this->properties)) {
            // Its not mutated, get the property
            return $this->properties[$property];
        }

        // Its not mutated or a property, kill
        return null;
    }

    /**
     * Magic setter to ensure we only set properties of the record.
     *
     * @param string $property The property name (such as "title").
     * @param mixed  $value    The value to set for the property
     *
     * @return void
     */
    public function __set($property, $value)
    {
        $this->mutatedProperties[$property] = $value;
    }

    /**
     * Refreshes the properties with a new set (usually after a save).
     *
     * @param array $properties The properties to set
     *
     * @return void
     */
    protected function refreshProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Resets the mutated properties (usually after a save).
     *
     * @return void
     */
    public function resetProperties()
    {
        $this->mutatedProperties = [];
    }

    /**
     * Forcefully get the original property even if its mutated.
     *
     * @param string $property The property to get.
     *
     * @return mixed
     */
    public function originalProperty($property)
    {
        return $this->properties[$property];
    }
}
