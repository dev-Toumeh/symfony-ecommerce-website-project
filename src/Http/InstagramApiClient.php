<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InstagramApiClient
{
    private const URL = 'https://instagram-scraper-api2.p.rapidapi.com/v1.2/posts';
    private const X_RAPID_API_HOST = 'instagram-scraper-api2.p.rapidapi.com';

    public function __construct(private HttpClientInterface $httpClient, private string $rapidApiKey)
    {
    }

    /**
     * @param mixed $rapidApiKey
     */
    public function fetchInstagramData(string $username): JsonResponse
    {
        $response = $this->httpClient->request('GET', self::URL, [
            'query'   => [
                'username_or_id_or_url' => $username,
            ],
            'headers' => [
                'x-rapidapi-host' => self::X_RAPID_API_HOST,
                'x-rapidapi-key'  => $this->rapidApiKey
            ]
    ]);

        if ($response->getStatusCode() !== JsonResponse::HTTP_OK) {
            return new JsonResponse('Instagram API Client Error ', JsonResponse::HTTP_BAD_REQUEST);
        }

        $posts = json_decode($response->getContent());
        $imagesUrls = [];
        foreach($posts->data->items as $item) {
          $imagesUrls[] = ['imageUrl' => $item->thumbnail_url];
        }
        return new JsonResponse($imagesUrls, JsonResponse::HTTP_OK);
    }

}
