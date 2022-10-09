<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Model\Factory\ClubFactory;
use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListClubOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListClubOperation
 */
final class ListClubOperationTest extends TestCase
{
    /**
     * @covers ::listClubsByDepartement
     */
    public function testListClubsByDepartement(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/clubs_by_departement.xml');
        $mock = new MockHandlerStub([
            new Response(200, [], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListClubOperation($FFTTClient, new ClubFactory(), new ArrayWrapper());

        $result = $operation->listClubsByDepartement(37);

        $this->assertCount(39, $result);

        $clubWithDate = $result[0];
        $clubWithoutDate = $result[1];

        $this->assertNotNull($date = $clubWithDate->getDateValidation());
        /** @var \DateTime $date */
        $this->assertSame('2022-07-06T00:00:00+00:00', $date->format(DATE_ATOM));
        $this->assertNull($clubWithoutDate->getDateValidation());

        $this->assertSame('4S TOURS T.T.', $clubWithDate->getNom());
        $this->assertSame('04370002', $clubWithDate->getNumero());
    }
}
