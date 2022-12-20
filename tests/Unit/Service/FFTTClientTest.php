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

    /**
     * @covers ::get
     * @covers ::send
     *
     * @param array<string, mixed> $responseHeaders
     *
     * @dataProvider getDecodingFromContentTypeData
     */
    public function testConvertEncoding(array $responseHeaders, string $expected): void
    {
        /** @var string $content */
        $content = file_get_contents(__DIR__.'/fixtures/response_with_accent.xml');

        $mock = new MockHandlerStub([
            new Response(200, $responseHeaders, $content),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $clientStub = new Client(['handler' => $handlerStack]);
        $client = new FFTTClient($clientStub, new UriGenerator('foo', 'bar'));

        /** @var array{foo: string} $response */
        $response = $client->get('bar');

        $this->assertSame($expected, $response['foo']);
    }

    /**
     * @return \Generator<array<mixed>>
     */
    public static function getDecodingFromContentTypeData(): \Generator
    {
        yield 'No header must not convert encoding' => [
            [
            ],
            'opÃ©ration',
        ];

        yield 'One charset UTF-8 header must convert encoding' => [
            [
                'content-type' => ['text/html; charset=UTF-8'],
            ],
            'opération',
        ];

        yield 'One charset ISO-8859-1 header must not convert encoding' => [
            [
                'content-type' => ['text/html; charset=ISO-8859-1'],
            ],
            'opÃ©ration',
        ];

        yield 'Multi charset header must use first' => [
            [
                'content-type' => ['text/html; charset=UTF-8', 'text/html; charset=ISO-8859-1'],
            ],
            'opération',
        ];
    }
}
