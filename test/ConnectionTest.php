<?php

namespace OhMyBrew\BasicShopifyResource\Test;

use OhMyBrew\BasicShopifyResource\Connection;

class ConnectionTest extends TestCase
{
    /**
     * @expectedException Exception
     */
    public function testThrowsExceptionForNoSetupConnection()
    {
        Connection::get();
    }

    /**
     * @expectedException Exception
     */
    public function testClearsConnection()
    {
        Connection::set(
            true,
            'example-shop.myshopify.com',
            [
                'key'      => '9798928b7bac29a732e3c1f3646732df2',
                'password' => 'dd69e76588e9008b0b8ae1dd7a7b7b59',
            ]
        );
        Connection::clear();

        Connection::get();
    }

    public function testCanSetPrivateConnection()
    {
        Connection::set(
            true,
            'example-shop.myshopify.com',
            [
                'key'      => '9798928b7bac29a732e3c1f3646732df2',
                'password' => 'dd69e76588e9008b0b8ae1dd7a7b7b59',
            ]
        );

        $this->assertNotNull(Connection::get());

        Connection::clear();
    }

    public function testCanSetPublicConnection()
    {
        Connection::set(
            false,
            'example-shop.myshopify.com',
            [
                'key'    => '9798928b7bac29a732e3c1f3646732df2',
                'secret' => 'dd69e76588e9008b0b8ae1dd7a7b7b59',
                'token'  => 'ee9398001ddeab7abbao29992001',
            ]
        );

        $this->assertNotNull(Connection::get());

        Connection::clear();
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsExceptionForMissingPrivateApiDetails()
    {
        Connection::set(
            false,
            'example-shop.myshopify.com',
            [
                'key' => '9798928b7bac29a732e3c1f3646732df2',
            ]
        );
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsExceptionForMissingPublicApiDetails()
    {
        Connection::set(
            true,
            'example-shop.myshopify.com',
            [
                'key' => '9798928b7bac29a732e3c1f3646732df2',
            ]
        );
    }
}
