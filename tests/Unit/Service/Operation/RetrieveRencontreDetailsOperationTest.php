<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Model\Factory\RencontreDetailsFactory;
use Alamirault\FFTTApi\Model\Rencontre\Joueur;
use Alamirault\FFTTApi\Model\Rencontre\Partie;
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
        $responseContentResult = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_1/rencontres_details_by_lien.xml');
        /** @var string $responseContentJoueursEquA */
        $responseContentJoueursEquA = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_1/liste_joueurs_equ_a.xml');
        /** @var string $responseContentJoueursEquB */
        $responseContentJoueursEquB = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_1/liste_joueurs_equ_b.xml');
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

    /**
     * @covers ::retrieveRencontreDetailsByLien
     */
    public function testRetrieveRencontreDetailsByLienWeirdFormattedPlayersNames(): void
    {
        /** @var string $responseContentResult */
        $responseContentResult = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_2/rencontres_details_by_lien.xml');
        /** @var string $responseContentJoueursEquB */
        $responseContentJoueursEquB = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_2/liste_joueurs_equ_a.xml');
        /** @var string $responseContentJoueursEquA */
        $responseContentJoueursEquA = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_2/liste_joueurs_equ_b.xml');
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
        $result = $operation->retrieveRencontreDetailsByLien('renc_id=1850692&is_retour=0&phase=2&res_1=25&res_2=17&equip_1=BESSANCOURT+3&equip_2=MONTSOULT+3&equip_id1=8702&equip_id2=9691&clubnum_1=08951366&clubnum_2=08950531', '08951366', '08950531');

        // Tests sur la rencontre
        $this->assertCount(4, $result->getJoueursA());
        $this->assertCount(4, $result->getJoueursB());
        $this->assertEquals(13.0, $result->getExpectedScoreEquipeA());
        $this->assertEquals(1.0, $result->getExpectedScoreEquipeB());
        $this->assertEquals('BESSANCOURT 3', $result->getNomEquipeA());
        $this->assertEquals('MONTSOULT 3', $result->getNomEquipeB());
        $this->assertEquals(25, $result->getScoreEquipeA());
        $this->assertEquals(17, $result->getScoreEquipeB());

        // Tests sur les joueurs
        $joueursA = array_values($result->getJoueursA());
        $joueursB = array_values($result->getJoueursB());
        $this->assertEquals(new Joueur('AMOR QUOINTEAU', 'Erwan', '9533978', 773, 'M'), $joueursA[0]);
        $this->assertEquals(new Joueur('MARIANNI-SAMSON', 'Emmanuel', '9233469', 723, 'M'), $joueursA[1]);
        $this->assertEquals(new Joueur('GARBANI', 'Fabrice', '9521619', 819, 'M'), $joueursA[2]);
        $this->assertEquals(new Joueur('GARBANI - LECOURT', 'Dimitri', '9536798', 633, 'M'), $joueursA[3]);
        $this->assertEquals(new Joueur('MACCHIETTI', 'Jean', '953581', 543, 'M'), $joueursB[0]);
        $this->assertEquals(new Joueur('DESROCHES', 'Damien', '9541048', 500, 'M'), $joueursB[1]);
        $this->assertEquals(new Joueur('BERNET', 'Loris', '9536698', 535, 'M'), $joueursB[2]);
        $this->assertEquals(new Joueur('HARDY', 'Mael', '9537596', 582, 'M'), $joueursB[3]);

        // Tests sur les parties
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan', 'MACCHIETTI Jean', 2, 1, ['04', '04', '08']), $result->getParties()[0]);
        $this->assertEquals(new Partie('MARIANNI-SAMSON Emmanuel', 'DESROCHES Damien', 2, 1, ['04', '05', '06']), $result->getParties()[1]);
        $this->assertEquals(new Partie('GARBANI Fabrice', 'BERNET Loris', 2, 1, ['01', '01', '04']), $result->getParties()[2]);
        $this->assertEquals(new Partie('GARBANI - LECOURT Dimitri', 'HARDY Mael', 1, 2, ['-08', '-05', '11', '11', '-10']), $result->getParties()[3]);
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan', 'DESROCHES Damien', 2, 1, ['06', '07', '02']), $result->getParties()[4]);
        $this->assertEquals(new Partie('MARIANNI-SAMSON Emmanuel', 'MACCHIETTI Jean', 2, 1, ['10', '04', '06']), $result->getParties()[5]);
        $this->assertEquals(new Partie('GARBANI - LECOURT Dimitri', 'BERNET Loris', 1, 2, ['-05', '-07', '-11']), $result->getParties()[6]);
        $this->assertEquals(new Partie('GARBANI Fabrice', 'HARDY Mael', 2, 1, ['04', '07', '06']), $result->getParties()[7]);
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan et MARIANNI-SAMSON Emmanuel', 'MACCHIETTI Jean et DESROCHES Damien', 2, 1, ['06', '05', '06']), $result->getParties()[8]);
        $this->assertEquals(new Partie('GARBANI Fabrice et GARBANI - LECOURT Dimitri', 'BERNET Loris et HARDY Mael', 2, 1, ['-09', '04', '07', '10']), $result->getParties()[9]);
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan', 'BERNET Loris', 2, 1, ['08', '10', '08']), $result->getParties()[10]);
        $this->assertEquals(new Partie('GARBANI Fabrice', 'MACCHIETTI Jean', 2, 1, ['01', '07', '02']), $result->getParties()[11]);
        $this->assertEquals(new Partie('GARBANI - LECOURT Dimitri', 'DESROCHES Damien', 1, 2, ['-08', '-08', '-10']), $result->getParties()[12]);
        $this->assertEquals(new Partie('MARIANNI-SAMSON Emmanuel', 'HARDY Mael', 2, 1, ['06', '05', '11']), $result->getParties()[13]);
    }
}
