<?php

namespace OhMyBrew\BasicShopifyResource\Test;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public function createResponse($fixture)
    {
        $response = new Response(
            200,
            [],
            file_get_contents(__DIR__."/fixtures/{$fixture}.json")
        );
    }
}