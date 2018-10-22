<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Models\Theme;
use OhMyBrew\BasicShopifyResource\Models\Asset;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class AssetTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new Asset());

        $this->assertEquals('assets', $props->resourcePath);
        $this->assertEquals('asset', $props->resourceName);
        $this->assertEquals('assets', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection(['models/theme', 'models/assets', 'models/themes']);
        $theme = $this->invokeMethod(Theme::class, 'find', [828155753]);

        // Assets (API call)
        $theme2 = $theme->assets->first()->theme;
        $this->assertInstanceOf(Theme::class, $theme2);
    }
}
