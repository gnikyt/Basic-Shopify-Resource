<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class ProductTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new Product());

        $this->assertEquals('products', $props->resourcePath);
        $this->assertEquals('product', $props->resourceName);
        $this->assertEquals('products', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection(['models/product', 'models/products']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertEquals(
            '/admin/products/632910392.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Product::class, $product);

        $products = $this->invokeMethod(Product::class, 'all');

        $this->assertEquals(
            '/admin/products.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $products);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection(['models/product', 'models/custom_collections', 'models/collects']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        // Variants (no API call)
        $variants = $product->variants;
        $this->assertInstanceOf(Collection::class, $variants);
        $this->assertTrue($variants->count() > 0);

        // Images (no API call)
        $images = $product->images;
        $this->assertInstanceOf(Collection::class, $images);
        $this->assertTrue($images->count() > 0);

        // Custom collections (API call)
        $collections = $product->collections;
        $this->assertInstanceOf(Collection::class, $collections);
        $this->assertTrue($collections->count() > 0);

        // Collect (API call)
        $collects = $product->collects;
        $this->assertInstanceOf(Collection::class, $collects);
        $this->assertTrue($collects->count() > 0);
    }
}
