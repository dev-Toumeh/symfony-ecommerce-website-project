<?php

namespace App\http;

interface FinanceApiClientInterface
{
    /**
     * @return void
     */
    public function fetchStockProfile(string $symbol, string $region);
}
