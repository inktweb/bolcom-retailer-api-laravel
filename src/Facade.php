<?php

namespace Inktweb\Bolcom\RetailerApi\Laravel;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Facade as LaravelFacade;
use Inktweb\Bolcom\RetailerApi\Client\Config;
use Inktweb\Bolcom\RetailerApi\Contracts\Client;
use Inktweb\Bolcom\RetailerApi\Laravel\Exceptions\ConfigException;
use Inktweb\Bolcom\RetailerApi\Clients;

class Facade extends LaravelFacade
{
    /** @var Client[] */
    protected static array $accounts = [];

    protected static function getFacadeAccessor(): string
    {
        return 'bolcom-retailer-api';
    }

    /**
     * @return Client|Clients\V8\Client|Clients\V9\Client|Clients\V10\Client
     * @throws ConfigException
     * @throws BindingResolutionException
     */
    public static function account(string $name): Client
    {
        return static::$accounts[$name]
            ?? static::$accounts[$name] = static::getAccount($name);
    }

    /**
     * @return Client|Clients\V8\Client|Clients\V9\Client|Clients\V10\Client
     * @throws BindingResolutionException
     * @throws ConfigException
     */
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
