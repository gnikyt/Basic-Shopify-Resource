<?php

namespace OhMyBrew\BasicShopifyResource\Test;

use ReflectionClass;
use OhMyBrew\BasicShopifyAPI;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use OhMyBrew\BasicShopifyResource\Resource;
use OhMyBrew\BasicShopifyResource\Connection;
use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\Variant;
use OhMyBrew\BasicShopifyResource\Models\Image;
use Tightenco\Collect\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ResourceTest extends TestCase
{
    protected function createConnection($fixtures = null)
    {
        if ($fixtures) {
            if (!is_array($fixtures)) {
                $fixtures = [$fixtures];
            }

            $response = [];
            foreach ($fixtures as $fixture) {
                $response[] = new Response(
                    200,
                    [],
                    file_get_contents(__DIR__."/fixtures/{$fixture}.json")
                );
            }

            $mock = new MockHandler($response);
            $client = new Client(['handler' => $mock]);
        }

        Connection::clear();
        Connection::set(
            true,
            'example-shop.myshopify.com',
            [
                'key' => '9798928b7bac29a732e3c1f3646732df2',
                'password' => 'dd69e76588e9008b0b8ae1dd7a7b7b59',
            ],
            $fixtures ? $client : null
        );

        return $fixtures ? ['client' => $client, 'mock' => $mock] : null;
    }

    protected function invokeMethod($className, $methodName, array $args = [])
    {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        
        return count($args) === 0 ? $method->invoke(new $className()) : $method->invokeArgs(new $className(), $args);
    }

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

    public function testFind()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertEquals(
            '/admin/products/632910392.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );
        $this->assertInstanceOf(Product::class, $product);
    }

    public function testFindThrough()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $connection = $this->createConnection('base/variant_find_through');
        $variant = $this->invokeMethod(Variant::class, 'findThrough', [808950810, $product]);

        $this->assertEquals(
            '/admin/products/632910392/variants/808950810.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );
        $this->assertInstanceOf(Variant::class, $variant);
    }

    public function testAll()
    {
        $connection = $this->createConnection('base/product_all');
        $products = $this->invokeMethod(Product::class, 'all');

        $this->assertEquals(
            '/admin/products.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );
        $this->assertInstanceOf(Collection::class, $products);
    }

    public function testAllThrough()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $connection = $this->createConnection('base/variant_all');
        $variants = $this->invokeMethod(Variant::class, 'allThrough', [$product]);

        $this->assertEquals(
            '/admin/products/632910392/variants.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );
        $this->assertInstanceOf(Collection::class, $variants);
    }

    public function testPk()
    {
        $product = new Product();
        $this->assertEquals('id', $product->getPk());

        $sample = new SampleResource();
        $this->assertEquals('gid', $sample->getPk());
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

    public function testRelations()
    {
        $connection = $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        // Test relational property detection
        $this->assertTrue($this->invokeMethod($product, 'isRelationalProperty', ['variants']));

        // Ensure a collection is returned on relation for has many
        $this->assertInstanceOf(Collection::class, $product->variants);

        // Since data for variants existed in initial call for products, no variant API call should've been made
        $this->assertEquals(
            '/admin/products/632910392.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );

        // Product does not exist for variant, so an API call should be made
        $connection = $this->createConnection('base/variant_find');
        $variant = $this->invokeMethod(Variant::class, 'find', [908950810]);

        $connection = $this->createConnection('base/product_find');
        $this->assertInstanceOf(Product::class, $variant->product);
        $this->assertEquals(
            '/admin/products/932910392.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );

        // Second call should now not fire an API call
        $this->assertInstanceOf(Product::class, $variant->product);

        // Test of hasMany
        $connection = $this->createConnection('base/variant_all');
        $this->assertInstanceOf(Collection::class, $product->hasMany(Variant::class));

        // Test of hasOne
        $connection = $this->createConnection('base/product_find');
        $this->assertInstanceOf(Product::class, $variant->hasOne(Product::class));
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

class SampleResource extends Resource
{
    protected $resourcePk = 'gid';
}