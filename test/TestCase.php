<?php

namespace OhMyBrew\BasicShopifyResource\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use OhMyBrew\BasicShopifyResource\Connection;
use ReflectionClass;
use ReflectionProperty;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function createResponse($fixture)
    {
        return new Response(
            200,
            [],
            file_get_contents(__DIR__."/fixtures/{$fixture}.json")
        );
    }

    protected function createConnection($fixtures = null)
    {
        if ($fixtures) {
            if (!is_array($fixtures)) {
                $fixtures = [$fixtures];
            }

            $response = [];
            foreach ($fixtures as $fixture) {
                $response[] = $this->createResponse($fixture);
            }

            $mock = new MockHandler($response);
            $client = new Client(['handler' => $mock]);
        }

        Connection::clear();
        Connection::set(
            true,
            'example-shop.myshopify.com',
            [
                'key'      => '9798928b7bac29a732e3c1f3646732df2',
                'password' => 'dd69e76588e9008b0b8ae1dd7a7b7b59',
            ],
            $fixtures ? $client : null
        );

        return $fixtures ? ['client' => $client, 'mock' => $mock] : null;
    }

    protected function getLastPathCalled($connection)
    {
        return parse_url($connection['mock']->getLastRequest()->getUri(), PHP_URL_PATH);
    }

    protected function invokeMethod($className, $methodName, array $args = [])
    {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return count($args) === 0 ? $method->invoke(new $className()) : $method->invokeArgs(new $className(), $args);
    }

    protected function getResourceProperties($resource)
    {
        $ref = new ReflectionClass($resource);
        $propList = $ref->getProperties(ReflectionProperty::IS_PROTECTED);

        $returnProps = [];
        foreach ($propList as $prop) {
            $property = $ref->getProperty($prop->getName());
            $property->setAccessible(true);

            $returnProps[$prop->getName()] = $property->getValue($resource);
        }

        return (object) $returnProps;
    }
}
