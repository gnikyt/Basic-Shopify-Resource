<?php

namespace OhMyBrew\BasicShopifyResource;

use OhMyBrew\BasicShopifyAPI;

/**
 * Handles the static API connection.
 */
class Connection
{
    /**
     * The properties of the resource, such as ID, title, etc.
     *
     * @var null|BasicShopifyAPI
     */
    protected static $connection = null;

    /**
     * Creates the connection.
     *
     * @param boolean $private Public or private API calls (default: public).
     * @param string  $shop    The shop to target.
     * @param array  $apiData  API details required.
     *
     * @return BasicShopifyAPI
     */
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

    /**
     * Gets the connection.
     *
     * @return BasicShopifyAPI
     */
    public static function get()
    {
        return self::$connection;
    }
}
