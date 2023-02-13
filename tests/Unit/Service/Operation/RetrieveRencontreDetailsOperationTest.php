<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Model\Factory\RencontreDetailsFactory;
use Alamirault\FFTTApi\Model\Rencontre\RencontreDetails;
use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\NomPrenomExtractor;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListJoueurOperation;
use Alamirault\FFTTApi\Service\Operation\RetrieveRencontreDetailsOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\RetrieveRencontreDetailsOperation
 */
final class RetrieveRencontreDetailsOperationTest extends TestCase
{
    /**
     * @covers ::retrieveRencontreDetailsByLien
     */
    public function testRetrieveRencontreDetailsByLien(): void
    {
        /** @var string $responseContentResult */
        $responseContentResult = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/rencontres_details_by_lien.xml');
        /** @var string $responseContentJoueursEquA */
        $responseContentJoueursEquA = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/liste_joueurs_equ_a.xml');
        /** @var string $responseContentJoueursEquB */
        $responseContentJoueursEquB = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/liste_joueurs_equ_b.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContentResult),
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContentJoueursEquA),
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContentJoueursEquB),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));
        $arrayWrapper = new ArrayWrapper();
        $listJoueurOperation = new ListJoueurOperation($FFTTClient, $arrayWrapper);
        $nomPrenomExtractor = new NomPrenomExtractor();
        $rencontreDetailsFactory = new RencontreDetailsFactory($nomPrenomExtractor, $listJoueurOperation);
        $operation = new RetrieveRencontreDetailsOperation($FFTTClient, $rencontreDetailsFactory);

        /** @var RencontreDetails $result */
        $result = $operation->retrieveRencontreDetailsByLien('renc_id=1568699&is_retour=0&phase=1&res_1=88&res_2=74&equip_1=HERBLAY+%282%29&equip_2=FRANCONVILLE+%281%29&equip_id1=19936&equip_id2=19937&clubnum_1=08950479&clubnum_2=08950103', '08950479', '08950103');
        $this->assertCount(9, $result->getJoueursA());
        $this->assertCount(9, $result->getJoueursB());
        $this->assertEquals(5, $result->getExpectedScoreEquipeA());
        $this->assertEquals(22, $result->getExpectedScoreEquipeB());
        $this->assertEquals('HERBLAY (2)', $result->getNomEquipeA());
        $this->assertEquals('FRANCONVILLE (1)', $result->getNomEquipeB());
        $this->assertEquals(88, $result->getScoreEquipeA());
        $this->assertEquals(74, $result->getScoreEquipeB());
    }
}
