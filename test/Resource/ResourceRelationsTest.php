<?php

namespace OhMyBrew\BasicShopifyResource\Test\Resource;

use OhMyBrew\BasicShopifyResource\Models\Product;
use OhMyBrew\BasicShopifyResource\Models\Variant;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class ResourceRelationsTest extends TestCase
{
    public function testRelationDetection()
    {
        $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertNotFalse($this->invokeMethod($product, 'getRelationalProperty', ['variants']));
    }

    /**
     * @expectedException Exception
     */
    public function testRelationDetectionFailure()
    {
        $this->createConnection('base/product_find');
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);
        $this->invokeMethod($product, 'getRelationship', ['nonexistant']);
    }

    public function testIncludesManyRelationship()
    {
        $connection = $this->createConnection(['base/product_find', 'base/product_find_empty_variants', 'base/variant_all']);

        // Since data for variants existed in initial call for products, no variant API call should've been made to variants endpoint
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertInstanceOf(Collection::class, $product->variants);
        $this->assertEquals(
            '/admin/products/632910392.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );

        // Product with no variants in response, should make an API call to get them
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertInstanceOf(Collection::class, $product->variants);
        $this->assertEquals(
            '/admin/products/632910392/variants.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );
    }

    public function testHasOneRelationship()
    {
        // Product does not exist for variant in the response, so an API call should be made
        $connection = $this->createConnection(['base/variant_find', 'base/product_all']);
        $variant = $this->invokeMethod(Variant::class, 'find', [908950810]);

        $this->assertInstanceOf(Product::class, $variant->product);
        $this->assertEquals(
            '/admin/products.json',
            parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH)
        );

        // Second call should not fire an API call since we now have the data
        $this->assertInstanceOf(Product::class, $variant->product);
    }

    public function testHasManyRelationship()
    {
        $this->createConnection(['base/product_find', 'base/custom_collection_all']);
        $product = $this->invokeMethod(Product::class, 'find', [632910392]);

        $this->assertInstanceOf(Collection::class, $product->collections);
    }
}
