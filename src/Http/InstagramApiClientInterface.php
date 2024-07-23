<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

interface InstagramApiClientInterface
{
    /**
     * @return JsonResponse
     */
    public function fetchInstagramData(string $username);
}
