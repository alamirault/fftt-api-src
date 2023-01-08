<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\ListRencontreOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListRencontreOperation
 */
final class ListRencontreOperationTest extends TestCase
{
    /**
     * @covers ::listRencontrePouleByLienDivision
     * This test covers the special case of the beginning of phase : D3 teams are assigned in pools that are not yet finalized and therefore empty just for a few hours (hence the cx_poule parameter is not defined in the lien_division param)
     */
    public function testListRencontrePouleByLienDivisionNoTours(): void
    {
        /** @var string $responseContent */
        $responseContent = '<?xml version="1.0" encoding="ISO-8859-1"?><liste></liste>';
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListRencontreOperation($FFTTClient);

        $result = $operation->listRencontrePouleByLienDivision('cx_poule=&D1=106641&organisme_pere=105');

        $this->assertCount(0, $result);
        $this->assertEquals([], $result);
    }

    /**
     * @covers ::listRencontrePouleByLienDivision
     */
    public function testListRencontrePouleByLienDivisionWithTours(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/rencontres_by_lien_division.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListRencontreOperation($FFTTClient);

        $result = $operation->listRencontrePouleByLienDivision('cx_poule=450762&D1=112049&organisme_pere=16');

        $this->assertCount(28, $result);

        $firstTour = $result[0];
        $this->assertSame('Poule 4 - tour n°1 du 16/12/2022', $firstTour->getLibelle());
        $this->assertSame('HERBLAY (2)', $firstTour->getNomEquipeA());
        $this->assertSame('CHAMBLY (1)', $firstTour->getNomEquipeB());
        $this->assertSame(82, $firstTour->getScoreEquipeA());
        $this->assertSame(80, $firstTour->getScoreEquipeB());
        $this->assertSame('renc_id=1568691&is_retour=0&phase=1&res_1=82&res_2=80&equip_1=HERBLAY+%282%29&equip_2=CHAMBLY+%281%29&equip_id1=19936&equip_id2=19995&clubnum_1=08950479&clubnum_2=08950092', $firstTour->getLien());

        $fithTour = $result[4];
        $this->assertSame('Poule 4 - tour n°2 du 27/01/2023', $fithTour->getLibelle());
        $this->assertSame('MONTSOULT (1)', $fithTour->getNomEquipeA());
        $this->assertSame('HERBLAY (2)', $fithTour->getNomEquipeB());
        $this->assertSame(0, $fithTour->getScoreEquipeA());
        $this->assertSame(0, $fithTour->getScoreEquipeB());
        $this->assertSame('renc_id=1568695&is_retour=0&phase=1&res_1=&res_2=&equip_1=MONTSOULT+%281%29&equip_2=HERBLAY+%282%29&equip_id1=19929&equip_id2=19936&clubnum_1=08950531&clubnum_2=08950479', $fithTour->getLien());
    }
}
