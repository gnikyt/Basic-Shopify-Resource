<?php

namespace OhMyBrew\BasicShopifyResource\Test\Resource;

use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\ProductVariant;
use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class ResourceFindTest extends TestCase
{
    public function testFind()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertEquals(
            '/admin/products/632910392.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Product::class, $product);
    }

    public function testFindThrough()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        // Find through resource
        $connection = $this->createConnection(['base/variant_find_through', 'base/variant_find_through']);
        $variant = $this->invokeMethod(ProductVariant::class, 'findThrough', [808950810, $product]);

        $this->assertEquals(
            '/admin/products/632910392/variants/808950810.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(ProductVariant::class, $variant);

        // Find through string
        $variant = $this->invokeMethod(ProductVariant::class, 'findThrough', [808950810, 'products/632910392']);

        $this->assertEquals(
            '/admin/products/632910392/variants/808950810.json',
            $this->getLastPathCalled($connection)
        );
    }

    public function testAll()
    {
        $connection = $this->createConnection('base/product_all');
        $products = $this->invokeMethod(Product::class, 'all');

        $this->assertEquals(
            '/admin/products.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $products);
    }

    public function testAllThrough()
    {
        $connection = $this->createConnection(['base/product_find', 'base/variant_all']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $variants = $this->invokeMethod(ProductVariant::class, 'allThrough', [$product]);

        $this->assertEquals(
            '/admin/products/632910392/variants.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $variants);
    }
}
