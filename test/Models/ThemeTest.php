<?php

namespace OhMyBrew\BasicShopifyResource\Test\Models;

use OhMyBrew\BasicShopifyResource\Models\Theme;
use OhMyBrew\BasicShopifyResource\Test\TestCase;
use Tightenco\Collect\Support\Collection;

class ThemeTest extends TestCase
{
    public function testSetup()
    {
        $props = $this->getResourceProperties(new Theme());

        $this->assertEquals('themes', $props->resourcePath);
        $this->assertEquals('theme', $props->resourceName);
        $this->assertEquals('themes', $props->resourceNamePlural);
        $this->assertEquals('id', $props->resourcePk);
    }

    public function testFinders()
    {
        $connection = $this->createConnection(['models/theme', 'models/themes']);
        $theme = $this->invokeMethod(Theme::class, 'find', [828155753]);

        $this->assertEquals(
            '/admin/themes/828155753.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Theme::class, $theme);

        $themes = $this->invokeMethod(Theme::class, 'all');

        $this->assertEquals(
            '/admin/themes.json',
            $this->getLastPathCalled($connection)
        );
        $this->assertInstanceOf(Collection::class, $themes);
    }

    public function testRelationships()
    {
        $connection = $this->createConnection(['models/theme', 'models/assets']);
        $theme = $this->invokeMethod(Theme::class, 'find', [828155753]);

        // Assets (API call)
        $assets = $theme->assets;
        $this->assertInstanceOf(Collection::class, $assets);
        $this->assertTrue($assets->count() > 0);
    }
}
