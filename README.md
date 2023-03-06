# Bol.com Retailer API Laravel bridge

This package is a Laravel bridge for the `inktweb/bolcom-retailer-api-php` package.

Its main functionality is providing a `BolcomRetailerApi` facade and a configuration file.
The facade has the ability to use multiple accounts.

## Installation

This package contains a service provider, which should be auto-discovered by Laravel. It
registers the `BolcomRetailerApi` facade, which can also be used directly through
`\Inktweb\Bolcom\RetailerApi\Laravel\Facade`. Furthermore, it also provides a minimal
configuration file, which can be published to your own project's `config` directory with

```shell
php artisan vendor:publish --tag=bolcom-retailer-api
```

## Configuration

The default configuration looks like this:

```php
use Inktweb\Bolcom\RetailerApi\Clients\V7\Client;

return [
    'client' => [
        'class' => Client::class,

        'default-account' => env('BOLCOM_RETAILER_API_DEFAULT_ACCOUNT', 'default'),

        'demo-mode' => env('BOLCOM_RETAILER_API_DEMO_MODE', true),
    ],

    'accounts' => [
        'default' => [
            'id' => env('BOLCOM_RETAILER_API_ID'),
            'secret' => env('BOLCOM_RETAILER_API_SECRET'),
        ],
    ],
];
```

`client.class` is the client class used to create the API client. In the example
above, the Bol.com V7 API client will be used. If you don't publish the configuration file,
the default client could change between major versions of this package. All other keys are
hopefully self-explanatory.

Keep in mind that you should **never** commit credentials into a repository! If you want to use
multiple accounts, either use different `.env` entries or create a separate file (JSON is a good
choice) that is excluded from the repository.

## Usage

When the `BolcomRetailerApi` facade is called, the account as specified in the
`bolcom-retailer-api.client.default-account` configuration entry will be used.

You can choose a different account by calling `BolcomRetailerApi::account($accountName)`.
From there, you can access the various client endpoints.

## Examples

```php
// Get the order with id 'foo-bar' with the default account.
BolcomRetailerApi::retailer()->orders()->getOrder('foo-bar');

// Get a paginated list of orders from the account 'some-account'.
BolcomRetailerApi::account('some-account')->retailer()->orders()->getOrders();

// You can also use the facade directly.
\Inktweb\Bolcom\RetailerApi\Laravel\Facade::account('my-other-company')->retailer()->orders()->getOrders();
```
