<?php

namespace OhMyBrew\BasicShopifyResource;

use OhMyBrew\BasicShopifyAPI;

class Connection
{
    protected static $connection = null;

    public static function set(bool $private = false, string $shop, array $apiData)
    {
        $api = new BasicShopifyAPI($private);
        $api->setShop($shop);
        $api->setApiKey($apiData['key']);

        if ($private) {
            $api->setApiPassword($apiData['password']);
        } else {
            $api->setApiSecret($apiData['secret']);
            $api->setAccessToken($apiData['token']);
        }

        self::$connection = $api;
    }

    public static function get()
    {
        return self::$connection;
    }
}
