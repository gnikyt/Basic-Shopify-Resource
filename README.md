# Basic Shopify Resource

This library is a simple wrapper for the Basic Shopify API to interact with the Shopify resources in a more friendly manner.

**THIS IS IN DEVELOPMENT** do not use. Library is changing, incomplete, and not yet unit tested.

## Examples:

```php
Connection::set(
    true, // false for public API
    'example-shop.myshopify.com',
    ['key' => '9798928b7bac29a732e3c1f3646732df2', 'password' => 'dd69e76588e9008b0b8ae1dd7a7b7b59']
);

$p = Product::find(1624265326631);
echo "Product: {$p->title}";
$p->title = 'New Title';
$p->save();

echo $p->variants()->first()->id;

echo Product::all()->count();

$v = Variant::findThrough(12999209309, $p);
echo $v->id;
```
