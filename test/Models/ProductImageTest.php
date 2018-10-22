<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\ProductImage;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class ProductImageTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new ProductImage());

        $this->assertEquals('images', $props->resourcePath);
        $this->assertEquals('image', $props->resourceName);
        $this->assertEquals('images', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection(['models/image', 'models/images']);
        $image = $this->invokeMethod(ProductImage::class, 'find', [850703190]);

        $this->assertEquals(
            '/admin/images/850703190.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(ProductImage::class, $image);

        $images = $this->invokeMethod(ProductImage::class, 'all');

        $this->assertEquals(
            '/admin/images.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $images);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection(['models/image', 'models/products']);
        $image = $this->invokeMethod(ProductImage::class, 'find', [850703190]);

        // Product (API call)
        $product = $image->product;
        $this->assertInstanceOf(Product::class, $product);
    }
}
