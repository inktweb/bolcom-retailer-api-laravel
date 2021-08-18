<?php

namespace Inktweb\Bolcom\RetailerApi\Laravel;

use Illuminate\Support\Facades\Facade as LaravelFacade;
use Inktweb\Bolcom\RetailerApi\Client\Config;
use Inktweb\Bolcom\RetailerApi\Contracts\Client;
use Inktweb\Bolcom\RetailerApi\Laravel\Exceptions\ConfigException;

class Facade extends LaravelFacade
{
    /** @var Client[] */
    protected static $accounts = [];

    protected static function getFacadeAccessor(): string
    {
        return 'bolcom-retailer-api';
    }

    public static function account(string $name): Client
    {
        return static::$accounts[$name]
            ?? static::$accounts[$name] = static::getAccount($name);
    }

    protected static function getAccount(string $name): Client
    {
        $config = static::getFacadeApplication()->make('config')->get('bolcom-retailer-api');

        $clientClassName = $config['client']['class'];
        $demoMode = $config['client']['demo-mode'];

        $account = $config['accounts'][$name] ?? null;

        if ($account === null) {
            throw new ConfigException("Account '{$name}' not found.");
        }

        if (empty($account['id']) || empty($account['secret'])) {
            throw new ConfigException("Configuration of '{$name}' is incomplete.");
        }

        return new $clientClassName(
            new Config(
                $account['id'],
                $account['secret'],
                $demoMode
            )
        );
    }
}
