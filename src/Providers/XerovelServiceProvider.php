<?php

namespace JPCaparas\Xerovel\Providers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use JPCaparas\Xerovel\Xerovel;
use JPCaparas\Xerovel\Contracts\Xerovel as XerovelContract;

/**
 * Class XerovelServiceProvider
 *
 * @package App\Providers
 */
class XerovelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/xerovel.php' => $this->app->configPath() . '/xerovel.php',
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function register()
    {
        $this->app->bind(XerovelContract::class, function () {
            $isAbsolutePath = '/^\/|\\\/';

            // Load from storage/app
            if (preg_match($isAbsolutePath, config('xerovel.rsa_private_key')) === 0) {
                $privateKeyPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . config('xerovel.rsa_private_key');
            } else {
                // Use absolute path
                $privateKeyPath = config('xerovel.rsa_private_key');
            }

            if (!is_readable($privateKeyPath)) {
                throw new FileNotFoundException('File ' . $privateKeyPath . ' could not be found');
            }

            $privateKey = file_get_contents($privateKeyPath);

            $config = [
                'oauth' => [
                    'callback_url'        => config('xerovel.callback_url'),
                    'consumer_key'    => config('xerovel.consumer_key'),
                    'consumer_secret' => config('xerovel.consumer_secret'),
                    'rsa_private_key' => $privateKey
                ],
            ];

            $client = new Xerovel();
            $client->setConfig($config);
            $client->setClient();

            return $client;
        });
    }
}
