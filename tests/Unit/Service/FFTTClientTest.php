<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\UriGenerator;
use Alamirault\FFTTApi\Tests\Unit\Service\Operation\MockHandlerStub;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\FFTTClient
 */
final class FFTTClientTest extends TestCase
{
    /**
     * @covers ::get
     * @covers ::send
     */
    public function testGetWithAccent(): void
    {
        /** @var string $content */
        $content = file_get_contents(__DIR__.'/fixtures/joueur_with_accent.xml');

        $mock = new MockHandlerStub([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $clientStub = new Client(['handler' => $handlerStack]);
        $client = new FFTTClient($clientStub, new UriGenerator('foo', 'bar'));

        /** @var array{licence: array<mixed>} $response */
        $response = $client->get('bar');

        $this->assertSame('Côme', $response['licence']['prenom']);
    }
}
