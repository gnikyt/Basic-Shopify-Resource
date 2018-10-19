<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class CustomCollection extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new CustomCollection());

        $this->assertEquals('custom_collections', $props->resourcePath);
        $this->assertEquals('custom_collection', $props->resourceName);
        $this->assertEquals('custom_collections', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection(['models/custom_collection', 'models/custom_collections']);
        $collection = $this->invokeMethod(CustomCollection::class, 'find', [1063001463]);

        $this->assertEquals(
            '/admin/custom_collections/1063001463.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(CustomCollection::class, $collection);

        $collections = $this->invokeMethod(Collection::class, 'all');

        $this->assertEquals(
            '/admin/custom_collections.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $collections);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection(['models/custom_collection', 'models/collects']);
        $collection = $this->invokeMethod(CustomCollection::class, 'find', [1063001463]);

        // Collects (API call)
        $collects = $collection->collects;
        $this->assertInstanceOf(Collection::class, $collects);
    }
}
