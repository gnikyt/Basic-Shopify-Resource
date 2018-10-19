<?php

namespace OhMyBrew\BasicShopifyResource\Test\Resource;

use OhMyBrew\BasicShopifyAPI;
use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\Variant;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use ReflectionClass;

class ResourceTest extends TestCase
{
    public function testCanGetConnection()
    {
        $this->createConnection();

        $this->assertInstanceOf(
            BasicShopifyAPI::class,
            $this->invokeMethod(Product::class, 'getConnection')
        );
    }

    public function testCanGetInstance()
    {
        $this->assertInstanceOf(
            Product::class,
            $this->invokeMethod(Product::class, 'getInstance')
        );
    }

    public function testProperties()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $ref = new ReflectionClass($product);
        $properties = $ref->getProperty('properties');
        $properties->setAccessible(true);
        $mutatedProperties = $ref->getProperty('mutatedProperties');
        $mutatedProperties->setAccessible(true);

        // Assert we have properties and mutations are none
        $this->assertTrue(count($properties->getValue($product)) > 0);
        $this->assertTrue(count($mutatedProperties->getValue($product)) === 0);

        // Test modification of a property
        $product->title = 'New Title';
        $this->assertTrue(count($mutatedProperties->getValue($product)) === 1);
        $this->assertEquals('New Title', $product->title);
        $this->assertEquals('IPod Nano - 8GB', $product->originalProperty('title'));

        // Test non-existant properties
        $this->assertNull($product->xyz);

        // Test reset of mutations
        $product->resetProperties();
        $this->assertTrue(count($mutatedProperties->getValue($product)) === 0);

        // Test refresh of properties
        $this->invokeMethod($product, 'refreshProperties', [['title' => 'IPod Nano - 8GB']]);
        $this->assertEquals('IPod Nano - 8GB', $product->title);

        // Test setting a relational property
        $variant = new Variant();

        $this->assertNull($variant->product_id);
        $variant->product = $product;
        $this->assertEquals(632910392, $variant->product_id);
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsErrorTryingToSetInvalidRelational()
    {
        $variant = new Variant();
        $variant->product = 1;
    }

    public function testIsNewAndIsExisting()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $this->assertFalse($product->isNew());
        $this->assertTrue($product->isExisting());

        $product = new Product();
        $this->assertTrue($product->isNew());
        $this->assertFalse($product->isExisting());
    }

    public function testSave()
    {
        $connection = $this->createConnection(['base/product_find', 'base/product_find']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $product->title = 'New Title';

        $ref = new ReflectionClass($product);
        $mutatedProperties = $ref->getProperty('mutatedProperties');
        $mutatedProperties->setAccessible(true);

        $this->assertTrue(count($mutatedProperties->getValue($product)) === 1);

        $product->save();

        $this->assertTrue(count($mutatedProperties->getValue($product)) === 0);
    }

    public function testDelete()
    {
        $connection = $this->createConnection(['base/product_find', 'base/product_find']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $product->destroy();

        $this->assertEquals('DELETE', $connection['mock']->getLastRequest()->getMethod());
    }
}
