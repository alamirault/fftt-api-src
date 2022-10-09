<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListActualiteOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListActualiteOperation
 */
final class ListActualiteOperationTest extends TestCase
{
    /**
     * @covers ::listActualites
     */
    public function testListActualites(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/actualite.xml');
        $mock = new MockHandlerStub([
            new Response(200, [], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListActualiteOperation($FFTTClient, new ArrayWrapper());

        $result = $operation->listActualites();

        $this->assertCount(10, $result);

        $actualite = $result[0];
        $this->assertSame('Ping santé', $actualite->getCategorie());
        $this->assertSame('2022-10-07T00:00:00+00:00', $actualite->getDate()->format(DATE_ATOM));
        $description = <<<TXT
Du 12 au 16 octobre, les Championnats du Monde Ping Parkinson se dérouleront à Pula en Croatie. Cinq Français seront présents lors de cette compétition.

Avoir la...
TXT;
        $this->assertSame($description, $actualite->getDescription());
        $this->assertSame('https://www.fftt.com/site/medias/news/news__20221007145001.jpg', $actualite->getPhoto());
        $this->assertSame('Les Championnats du Monde Ping Parkinson débutent mercredi !', $actualite->getTitre());
        $this->assertSame('https://www.fftt.com/site/actualites/2022-10-07/les-championnats-monde-ping-parkinson-debutent-mercredi', $actualite->getUrl());
    }
}
