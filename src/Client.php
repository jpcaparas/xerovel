<?php

namespace JPCaparas\Xerovel;

use XeroPHP\Application\PrivateApplication as BaseClient;
use JPCaparas\Xerovel\Contracts\Client as ClientContract;

/**
 * Class Client
 *
 * @package JPCaparas\Xerovel
 */
class Client extends BaseClient implements ClientContract
{
    /**
     * Client constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }
}
