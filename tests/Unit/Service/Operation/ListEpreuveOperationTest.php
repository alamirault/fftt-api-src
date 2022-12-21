<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\ListEpreuveOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListEpreuveOperation
 */
final class ListEpreuveOperationTest extends TestCase
{
    /**
     * @covers ::listEpreuves
     */
    public function testListEpreuves(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/epreuves.xml');
        $mock = new MockHandlerStub([
            new Response(200, [], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListEpreuveOperation($FFTTClient);

        $result = $operation->listEpreuves('E', 105);

        $this->assertCount(3, $result);

        $equipe = $result[0];

        $this->assertSame(258, $equipe->getIdEpreuve());
        $this->assertSame(1, $equipe->getIdOrga());
        $this->assertSame('Chpt France par equipes feminin', $equipe->getLibelle());
        $this->assertSame('H', $equipe->getTypeEpreuve());
    }
}
