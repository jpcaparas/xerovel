# Xerovel

A Laravel service provider for the [`calcinai/xero-php` Xero PHP API client](https://github.com/calcinai/xero-php).

## Installation

1. Publish the config files:

        php artisan vendor:publish --provider=JPCaparas\\Xerovel\\Providers\\XerovelServiceProvider

1. Set the configuration at `./config/xerovel.php`.

1. Add Xerovel on the list of package service providers at `./config/app.php`:

        /*
         * Package Service Providers...
         */
        [...]
        JPCaparas\Xerovel\Providers\XerovelServiceProvider::class,
        [...]

## TODO

- Tests
