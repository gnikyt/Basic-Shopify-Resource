<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Models\Image;
use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\Variant;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class VariantTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new Variant());

        $this->assertEquals('variants', $props->resourcePath);
        $this->assertEquals('variant', $props->resourceName);
        $this->assertEquals('variants', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection(['models/product', 'models/variant', 'models/variants']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $variant = $this->invokeMethod(Variant::class, 'findThrough', [808950810, $product]);

        $this->assertEquals(
            '/admin/products/632910392/variants/808950810.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Variant::class, $variant);

        $variants = $this->invokeMethod(Variant::class, 'allThrough', [$product]);

        $this->assertEquals(
            '/admin/products/632910392/variants.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $variants);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection([
            'models/product',
            'models/variant',
            'models/products',
            'models/product',
            'models/image',
        ]);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $variant = $this->invokeMethod(Variant::class, 'findThrough', [808950810, $product]);

        // Product (API call)
        $product = $variant->product;
        $this->assertInstanceOf(Product::class, $product);

        // Image (API call)
        $image = $variant->image;
        $this->assertInstanceOf(Image::class, $image);
    }
}
