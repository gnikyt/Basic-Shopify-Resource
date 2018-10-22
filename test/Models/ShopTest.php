<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Test\TestCase;
use OhMyBrew\BasicShopifyResource\Models\Shop;

class ShopTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new Shop());

        $this->assertEquals('shop', $props->resourceName);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection('models/shop');
        $shop = $this->invokeMethod(Shop::class, 'find', [0]);

        $this->assertEquals(
            '/admin/shop.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(SHop::class, $shop);
    }
}
