<?php

namespace JPCaparas\Xerovel;

use JPCaparas\Xerovel\Contracts\Xerovel as XerovelContract;
use XeroPHP\Application\PrivateApplication as BaseClient;

class Xerovel implements XerovelContract
{
    /**
     * @var BaseClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $config = [];

    public function setConfig(array $config = null)
    {
        $this->validateConfig($config);

        $this->config = $config ?? [];
    }

    protected function validateConfig($config): void {
        if (empty($config['oauth'])) {
            throw new \InvalidArgumentException('OAuth configuration should exist.');
        }

        [
            'callback_url' => $callbackUrl,
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
            'rsa_private_key' => $rsaPrivateKey
        ] = $config['oauth'];

        if (!$callbackUrl) {
            throw new \InvalidArgumentException('Callback URL must be specified.');
        }

        if (!$consumerKey || !$consumerSecret) {
            throw new \InvalidArgumentException('Consumer key & secret must not be empty.');
        }

        if (!$rsaPrivateKey) {
            throw new \InvalidArgumentException('RSA private key must not be empty.');
        }
    }

    public function setClient(?BaseClient $client = null): self
    {
        if ($client) {
            if (!$client instanceof BaseClient) {
                throw new \InvalidArgumentException('Client must be instance of ' . BaseClient::class);
            }

            $this->client = $client;

            return $this;
        }

        if (empty($this->config)) {
            throw new \Exception('Configuration must be set.');
        }

        $this->client = new BaseClient($this->config);

        return $this;
    }

    public function getClient(): BaseClient
    {
        if (!$this->client instanceof BaseClient) {
            throw new \Exception('Client must be instance of ' . BaseClient::class);
        }

        return $this->client;
    }
}
