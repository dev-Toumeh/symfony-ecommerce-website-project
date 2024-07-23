<?php

namespace App\Tests\integration;

use App\Http\InstagramApiClientInterface;
use App\Tests\DatabaseDependantTestCase;

class InstagramApiClientTest extends DatabaseDependantTestCase
{

    public function testUrlsAreValidAndHttps(): void
    {
        /** @var InstagramApiClientInterface $instagramApiClient */
        $instagramApiClient = self::$kernel->getContainer()->get('instagram-api-client');
        $instagramUrls = $instagramApiClient->fetchInstagramData('mrbeast');
        $instagramUrls = json_decode($instagramUrls->getContent(), true);

        foreach ($instagramUrls as $instagramUrl) {
            $this->assertNotEmpty($instagramUrl, "URL should not be empty");
            $this->assertTrue(filter_var($instagramUrl, FILTER_VALIDATE_URL) !== false, "URL should be valid");
            $this->assertStringStartsWith('https', $instagramUrl, "URL should use HTTPS");
        }
    }

}
