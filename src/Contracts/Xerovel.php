<?php

namespace JPCaparas\Xerovel\Contracts;

use XeroPHP\Application\PrivateApplication as BaseClient;

interface Xerovel
{
    public function setConfig(array $config);

    public function setClient(?BaseClient $client);

    public function getClient() : BaseClient;
}
