<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Integration;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class AbstractIntegrationTestCase extends TestCase
{
    protected function getFFTTClient(): FFTTClient
    {
        $id = getenv('FFTT_ID');
        $password = getenv('FFTT_PASSWORD');

        if (false === $id || false === $password) {
            $this->markTestSkipped('Missing FFTT_ID or FFTT_PASSWORD environment variable');
        }

        return new FFTTClient(new Client(), new UriGenerator($id, $password));
    }
}
