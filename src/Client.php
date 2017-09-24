<?php

namespace JPCaparas\Xerovel;

use XeroPHP\Application\PrivateApplication;

class Client extends PrivateApplication
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
