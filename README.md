# Basic Shopify Resource

[![Build Status](https://travis-ci.org/ohmybrew/Basic-Shopify-Resource.svg?branch=master)](http://travis-ci.org/ohmybrew/Basic-Shopify-Resource)
[![Coverage Status](https://coveralls.io/repos/github/ohmybrew/Basic-Shopify-Resource/badge.svg?branch=master)](https://coveralls.io/github/ohmybrew/Basic-Shopify-Resource?branch=master)
[![StyleCI](https://styleci.io/repos/153016975/shield?branch=master)](https://styleci.io/repos/153016975)
[![License](https://poser.pugx.org/ohmybrew/basic-shopify-resource/license)](https://packagist.org/packages/ohmybrew/basic-shopify-resource)

This library is a simple wrapper for the Basic Shopify API to interact with the Shopify resources in a more friendly manner.

**THIS IS IN DEVELOPMENT** do not use, unit tests are on-going.

## Examples:

```php
# Setting up a static connection
Connection::set(
    true, // false for public API
    'example-shop.myshopify.com',
    ['key' => '9798928b7bac29a732e3c1f3646732df2', 'password' => 'dd69e76588e9008b0b8ae1dd7a7b7b59']
);
```

```php
$product = Product::find(1624265326631);
echo "Product: {$product->title}";
$product->title = 'New Title';
$product->save();

echo $p->variants->first()->id;
print_r($p->varianrs->first()->image->src); // Gets the variant image (lazy loaded)
print_r($p->variants->first()->product); // Gets product for variant (lazy loaded)
print_r($p->collections->first()->collects); // Gets collects for the collection (lazy loaded)

$count = Product::all()->count();
echo "There are {$count} products";

$variant = Variant::findThrough(12999209309, $product);
echo $variant->id;

$collection = CustomCollection::find(29889201111);
```
