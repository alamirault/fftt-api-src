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
        $listJoueurOperation = new ListJoueurOperation($FFTTClient, new ArrayWrapper(), new NomPrenomExtractor());
        $rencontreDetailsFactory = new RencontreDetailsFactory(new NomPrenomExtractor(), $listJoueurOperation);
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
        $listJoueurOperation = new ListJoueurOperation($FFTTClient, new ArrayWrapper(), new NomPrenomExtractor());
        $rencontreDetailsFactory = new RencontreDetailsFactory(new NomPrenomExtractor(), $listJoueurOperation);
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
        $this->assertEquals(new Joueur('GARBANI-LECOURT', 'Dimitri', '9536798', 633, 'M'), $joueursA[3]);
        $this->assertEquals(new Joueur('MACCHIETTI', 'Jean', '953581', 543, 'M'), $joueursB[0]);
        $this->assertEquals(new Joueur('SÉJOURNÉ', 'Jérémy', '9541048', 500, 'M'), $joueursB[1]);
        $this->assertEquals(new Joueur('BERNET', 'Loris', '9536698', 535, 'M'), $joueursB[2]);
        $this->assertEquals(new Joueur('HARDY', 'Mael', '9537596', 582, 'M'), $joueursB[3]);

        // Tests sur les parties
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan', 'MACCHIETTI Jean', 2, 1, [4, 4, 8]), $result->getParties()[0]);
        $this->assertEquals(new Partie('MARIANNI-SAMSON Emmanuel', 'SÉJOURNÉ Jérémy', 2, 1, [4, 5, 6]), $result->getParties()[1]);
        $this->assertEquals(new Partie('GARBANI Fabrice', 'BERNET Loris', 2, 1, [1, 1, 4]), $result->getParties()[2]);
        $this->assertEquals(new Partie('GARBANI-LECOURT Dimitri', 'HARDY Mael', 1, 2, [-8, -5, 11, 11, -10]), $result->getParties()[3]);
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan', 'SÉJOURNÉ Jérémy', 2, 1, [6, 7, 2]), $result->getParties()[4]);
        $this->assertEquals(new Partie('MARIANNI-SAMSON Emmanuel', 'MACCHIETTI Jean', 2, 1, [10, 4, 6]), $result->getParties()[5]);
        $this->assertEquals(new Partie('GARBANI-LECOURT Dimitri', 'BERNET Loris', 1, 2, [-5, -7, -11]), $result->getParties()[6]);
        $this->assertEquals(new Partie('GARBANI Fabrice', 'HARDY Mael', 2, 1, [4, 7, 6]), $result->getParties()[7]);
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan et MARIANNI-SAMSON Emmanuel', 'MACCHIETTI Jean et SÉJOURNÉ Jérémy', 2, 1, [6, 5, 6]), $result->getParties()[8]);
        $this->assertEquals(new Partie('GARBANI Fabrice et GARBANI-LECOURT Dimitri', 'BERNET Loris et HARDY Mael', 2, 1, [-9, 4, 7, 10]), $result->getParties()[9]);
        $this->assertEquals(new Partie('AMOR QUOINTEAU Erwan', 'BERNET Loris', 2, 1, [8, 10, 8]), $result->getParties()[10]);
        $this->assertEquals(new Partie('GARBANI Fabrice', 'MACCHIETTI Jean', 2, 1, [1, 7, 2]), $result->getParties()[11]);
        $this->assertEquals(new Partie('GARBANI-LECOURT Dimitri', 'SÉJOURNÉ Jérémy', 1, 2, [-8, -8, -10]), $result->getParties()[12]);
        $this->assertEquals(new Partie('MARIANNI-SAMSON Emmanuel', 'HARDY Mael', 2, 1, [6, 5, 11]), $result->getParties()[13]);
    }

    /**
     * This test covers when a whole team is forfeit.
     *
     * @covers ::retrieveRencontreDetailsByLien
     */
    public function testRetrieveRencontreDetailsByLienWholeTeamForfeit(): void
    {
        /** @var string $responseContentResult */
        $responseContentResult = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_whole_team_wo/rencontres_details_by_lien.xml');
        /** @var string $responseContentJoueursEquA */
        $responseContentJoueursEquA = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_whole_team_wo/liste_joueurs_equ_a.xml');
        /** @var string $responseContentJoueursEquB */
        $responseContentJoueursEquB = '<?xml version="1.0" encoding="ISO-8859-1"?><liste></liste>';
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
        $listJoueurOperation = new ListJoueurOperation($FFTTClient, new ArrayWrapper(), new NomPrenomExtractor());
        $rencontreDetailsFactory = new RencontreDetailsFactory(new NomPrenomExtractor(), $listJoueurOperation);
        $operation = new RetrieveRencontreDetailsOperation($FFTTClient, $rencontreDetailsFactory);

        /** @var RencontreDetails $result */
        $result = $operation->retrieveRencontreDetailsByLien('renc_id=1704115&is_retour=0&phase=2&res_1=28&res_2=0&equip_1=BESSANCOURT+1&equip_2=GROSLAY+2&equip_id1=8740&equip_id2=8741&clubnum_1=08951366&clubnum_2=08950348', '08951366', '08950348');

        // Tests sur la rencontre
        $this->assertCount(4, $result->getJoueursA());
        $this->assertCount(0, $result->getJoueursB());
        $this->assertEquals(1.0, $result->getExpectedScoreEquipeA());
        $this->assertEquals(13.0, $result->getExpectedScoreEquipeB());
        $this->assertEquals('BESSANCOURT 1', $result->getNomEquipeA());
        $this->assertEquals('GROSLAY 2', $result->getNomEquipeB());
        $this->assertEquals(28, $result->getScoreEquipeA());
        $this->assertEquals(0, $result->getScoreEquipeB());

        // Tests sur les joueurs
        $joueursA = array_values($result->getJoueursA());
        $this->assertEquals(new Joueur('LEFEVRE', 'Philippe', '9527872', 1328, 'M'), $joueursA[0]);
        $this->assertEquals(new Joueur('BOUVET', 'Anthony', '4512672', 994, 'M'), $joueursA[1]);
        $this->assertEquals(new Joueur('CLEMENT', 'Stephane', '9222438', 970, 'M'), $joueursA[2]);
        $this->assertEquals(new Joueur('NEE', 'Christophe', '9526243', 866, 'M'), $joueursA[3]);
        $this->assertEquals([], $result->getJoueursB());
    }

    /**
     * This test covers when a player is forfeit.
     *
     * @covers ::retrieveRencontreDetailsByLien
     */
    public function testRetrieveRencontreDetailsByLienPlayerForfeit(): void
    {
        /** @var string $responseContentResult */
        $responseContentResult = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_player_wo/rencontres_details_by_lien.xml');
        /** @var string $responseContentJoueursEquA */
        $responseContentJoueursEquA = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_player_wo/liste_joueurs_equ_a.xml');
        /** @var string $responseContentJoueursEquB */
        $responseContentJoueursEquB = file_get_contents(__DIR__.'/../fixtures/RetrieveRencontreDetailsOperationTest/test_player_wo/liste_joueurs_equ_b.xml');
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
        $listJoueurOperation = new ListJoueurOperation($FFTTClient, new ArrayWrapper(), new NomPrenomExtractor());
        $rencontreDetailsFactory = new RencontreDetailsFactory(new NomPrenomExtractor(), $listJoueurOperation);
        $operation = new RetrieveRencontreDetailsOperation($FFTTClient, $rencontreDetailsFactory);

        /** @var RencontreDetails $result */
        $result = $operation->retrieveRencontreDetailsByLien('renc_id=1705548&is_retour=0&phase=2&res_1=11&res_2=27&equip_1=LA+FRETTE+4&equip_2=EZANVILLE+ECOUEN+14&equip_id1=11869&equip_id2=21323&clubnum_1=08951331&clubnum_2=08950481', '08951331', '08950481');

        // Tests sur la rencontre
        $this->assertCount(3, $result->getJoueursA());
        $this->assertCount(4, $result->getJoueursB());
        $this->assertEquals(5.0, $result->getExpectedScoreEquipeA());
        $this->assertEquals(9.0, $result->getExpectedScoreEquipeB());
        $this->assertEquals('LA FRETTE 4', $result->getNomEquipeA());
        $this->assertEquals('EZANVILLE ECOUEN 14', $result->getNomEquipeB());
        $this->assertEquals(11, $result->getScoreEquipeA());
        $this->assertEquals(27, $result->getScoreEquipeB());

        // Tests sur les joueurs
        $joueursA = array_values($result->getJoueursA());
        $joueursB = array_values($result->getJoueursB());
        $this->assertEquals(new Joueur('NICOLAS', 'Xavier', '9540536', 500, 'M'), $joueursA[0]);
        $this->assertEquals(new Joueur('TOUFANIAN', 'Armen', '9518355', 500, 'M'), $joueursA[1]);
        $this->assertEquals(new Joueur('TABIB', 'Talel', '9541836', 500, 'M'), $joueursA[2]);
        $this->assertEquals(new Joueur('COSSEC', 'Kenji', '9535888', 504, 'M'), $joueursB[0]);
        $this->assertEquals(new Joueur('MENGIN', 'Luca', '9534049', 500, 'M'), $joueursB[1]);
        $this->assertEquals(new Joueur('WATTEBLED', 'Morgane', '9318432', 531, 'F'), $joueursB[2]);
        $this->assertEquals(new Joueur('GODIN', 'Emeline', '9539281', 518, 'F'), $joueursB[3]);

        // Tests sur les parties
        $this->assertEquals(new Partie('NICOLAS Xavier', 'COSSEC Kenji', 2, 1, [6, 6, 8]), $result->getParties()[0]);
        $this->assertEquals(new Partie('TOUFANIAN Armen', 'MENGIN Luca', 1, 2, [-6, -3, 10, -7]), $result->getParties()[1]);
        $this->assertEquals(new Partie('TABIB Talel', 'WATTEBLED Morgane', 1, 2, [-5, -10, 10, -3]), $result->getParties()[2]);
        $this->assertEquals(new Partie('Absent Absent', 'GODIN Emeline', 0, 2, [0, 0, 0]), $result->getParties()[3]);
        $this->assertEquals(new Partie('NICOLAS Xavier', 'MENGIN Luca', 1, 2, [10, -7, -8, -12]), $result->getParties()[4]);
        $this->assertEquals(new Partie('TOUFANIAN Armen', 'COSSEC Kenji', 1, 2, [-10, 6, -9, -5]), $result->getParties()[5]);
        $this->assertEquals(new Partie('Absent Absent', 'WATTEBLED Morgane', 0, 2, [0, 0, 0]), $result->getParties()[6]);
        $this->assertEquals(new Partie('TABIB Talel', 'GODIN Emeline', 1, 2, [-7, -1, -8]), $result->getParties()[7]);
        $this->assertEquals(new Partie('NICOLAS Xavier et TOUFANIAN Armen', 'WATTEBLED Morgane et GODIN Emeline', 1, 2, [-8, -9, 9, 5, -3]), $result->getParties()[8]);
        $this->assertEquals(new Partie('Absent Absent', 'COSSEC Kenji et MENGIN Luca', 0, 2, [0, 0, 0]), $result->getParties()[9]);
        $this->assertEquals(new Partie('NICOLAS Xavier', 'WATTEBLED Morgane', 1, 2, [-7, -6, -6]), $result->getParties()[10]);
        $this->assertEquals(new Partie('TABIB Talel', 'COSSEC Kenji', 1, 2, [-4, -8, 8, 10, -7]), $result->getParties()[11]);
        $this->assertEquals(new Partie('Absent Absent', 'MENGIN Luca', 0, 2, [0, 0, 0]), $result->getParties()[12]);
        $this->assertEquals(new Partie('TOUFANIAN Armen', 'GODIN Emeline', 1, 2, [-7, -7, -7]), $result->getParties()[13]);
    }
}
