<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListEquipeOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListEquipeOperation
 */
final class ListEquipeOperationTest extends TestCase
{
    /**
     * @covers ::listEquipesByClub
     */
    public function testListEquipesByClub(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/equipes.xml');
        $mock = new MockHandlerStub([
            new Response(200, [], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListEquipeOperation($FFTTClient, new ArrayWrapper());

        $result = $operation->listEquipesByClub('04370690');

        $this->assertCount(3, $result);

        $equipe = $result[0];

        $this->assertSame('A.P. ST SENOCH 2 - Phase 1', $equipe->getLibelle());
        $this->assertSame('D37_D?PARTEMENTALE_3_Ph1 Poule 2', $equipe->getDivision());
        $this->assertSame('cx_poule=436904&D1=106653&organisme_pere=45', $equipe->getLienDivision());
    }
}
