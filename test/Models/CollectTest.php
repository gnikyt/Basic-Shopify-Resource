<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Test\TestCase;
use OhMyBrew\BasicShopifyResource\Models\Collect;
use OhMyBrew\BasicShopifyResource\Models\CustomCollection;
use OhMyBrew\BasicShopifyResource\Models\Product;
use Tightenco\Collect\Support\Collection;

class CollectTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new Collect());

        $this->assertEquals('collects', $props->resourcePath);
        $this->assertEquals('collect', $props->resourceName);
        $this->assertEquals('collects', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection(['models/collect', 'models/collects']);
        $collect = $this->invokeMethod(Collect::class, 'find', [841564295]);

        $this->assertEquals(
            '/admin/collects/841564295.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collect::class, $collect);

        $collects = $this->invokeMethod(Collect::class, 'all');

        $this->assertEquals(
            '/admin/collects.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $collects);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection(['models/collect', 'models/products', 'models/custom_collections']);
        $collect = $this->invokeMethod(Collect::class, 'find', [841564295]);

        // Product (API call)
        $product = $collect->product;
        $this->assertInstanceOf(Product::class, $product);

        // Collection (API call)
        $collection = $collect->collection;
        $this->assertInstanceOf(CustomCollection::class, $collection);
    }
}