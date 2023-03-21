<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Exception\ClubNotFoundException;
use Alamirault\FFTTApi\Exception\InvalidRequestException;
use Alamirault\FFTTApi\Exception\JoueurNotFoundException;
use Alamirault\FFTTApi\Model\Enums\NationaliteEnum;
use Alamirault\FFTTApi\Model\Enums\TypeLicenceEnum;
use Alamirault\FFTTApi\Model\JoueurDetails;
use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\RetrieveJoueurDetailsOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\RetrieveJoueurDetailsOperation
 */
final class RetrieveJoueurDetailsOperationTest extends TestCase
{
    /**
     * Cas d'un joueur existant sans club.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurExistantSansClub(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/RetrieveJoueurDetailsOperationTest/joueur_existant_sans_club.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        /** @var JoueurDetails $joueurDetails */
        $joueurDetails = $operation->retrieveJoueurDetails('3418930');

        $this->assertSame(639188, $joueurDetails->getIdLicence());
        $this->assertSame('3418930', $joueurDetails->getLicence());
        $this->assertSame('LEBRUN', $joueurDetails->getNom());
        $this->assertSame('Alexis', $joueurDetails->getPrenom());
        $this->assertSame('11340010', $joueurDetails->getNumClub());
        $this->assertSame('MONTPELLIER TT', $joueurDetails->getNomClub());
        $this->assertTrue($joueurDetails->isHomme());
        $this->assertSame(TypelicenceEnum::Competition, $joueurDetails->getTypeLicence());
        $this->assertSame('01/08/2022', $joueurDetails->getDateValidation()?->format('d/m/Y'));
        $this->assertTrue($joueurDetails->isClasseNational());
        $this->assertSame('S', $joueurDetails->getCategorie());
        $this->assertSame(5, $joueurDetails->getClassementNational());
        $this->assertSame(28.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(3508.0, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(3453.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(3508.0, $joueurDetails->getPointsLicence());
        $this->assertSame(NationaliteEnum::Etrangère, $joueurDetails->getNationalite());
        $this->assertSame('21/02/2022', $joueurDetails->getDateMutation()?->format('d/m/Y'));
        $this->assertNull($joueurDetails->getDiplomeArbitre());
        $this->assertNull($joueurDetails->getDiplomeJugeArbitre());
        $this->assertNull($joueurDetails->getDiplomeTechnique());
    }

    /**
     * Cas d'un joueur existant avec club existant.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurExistantAvecClub(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/RetrieveJoueurDetailsOperationTest/joueur_existant_club_existant.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        /** @var JoueurDetails $joueurDetails */
        $joueurDetails = $operation->retrieveJoueurDetails('2221557', '08950978');

        $this->assertSame(1370316, $joueurDetails->getIdLicence());
        $this->assertSame('2221557', $joueurDetails->getLicence());
        $this->assertSame('LE MORVAN', $joueurDetails->getNom());
        $this->assertSame('Sébastien', $joueurDetails->getPrenom());
        $this->assertSame('08950978', $joueurDetails->getNumClub());
        $this->assertSame('EAUBONNE CSM TT', $joueurDetails->getNomClub());
        $this->assertTrue($joueurDetails->isHomme());
        $this->assertSame(TypelicenceEnum::Competition, $joueurDetails->getTypeLicence());
        $this->assertSame('18/09/2022', $joueurDetails->getDateValidation()?->format('d/m/Y'));
        $this->assertFalse($joueurDetails->isClasseNational());
        $this->assertSame('V1', $joueurDetails->getCategorie());
        $this->assertNull($joueurDetails->getClassementNational());
        $this->assertSame(807.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(775.25, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(710.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(807.0, $joueurDetails->getPointsLicence());
        $this->assertSame(NationaliteEnum::Européenne, $joueurDetails->getNationalite());
        $this->assertSame('01/07/2022', $joueurDetails->getDateMutation()?->format('d/m/Y'));
        $this->assertNull($joueurDetails->getDiplomeArbitre());
        $this->assertNull($joueurDetails->getDiplomeJugeArbitre());
        $this->assertNull($joueurDetails->getDiplomeTechnique());
    }

    /**
     * Cas d'un joueur non existant sans club.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurNonExistantSansClub(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/empty_result.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);
        $licence = '0000000';

        try {
            $operation->retrieveJoueurDetails($licence);
        } catch (\Exception $e) {
            $this->assertSame("Joueur '0000000' does not exist", $e->getMessage());
            $this->assertSame(JoueurNotFoundException::class, $e::class);
        }
    }

    /**
     * Cas d'un joueur existant avec club non existant.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurExistantAvecClubNonExistant(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/server_error.json');
        $mock = new MockHandlerStub([
            new Response(500, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);
        $licence = '9529825';
        $club = '0000000';

        try {
            $operation->retrieveJoueurDetails($licence, $club);
        } catch (\Exception $e) {
            $this->assertSame("Club '0000000' does not exist", $e->getMessage());
            $this->assertSame(ClubNotFoundException::class, $e::class);
        }
    }

    /**
     * Cas d'un joueur non existant avec club existant.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurNonExistantAvecClubExistant(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/empty_result.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);
        $licence = '0000000';
        $club = '08950330';

        try {
            $operation->retrieveJoueurDetails($licence, $club);
        } catch (\Exception $e) {
            $this->assertSame("Joueur '0000000' does not exist in club '08950330'", $e->getMessage());
            $this->assertSame(JoueurNotFoundException::class, $e::class);
        }
    }

    /**
     * Cas d'un joueur non existant avec club non existant.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurNonExistantAvecClubNonExistant(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/server_error.json');
        $mock = new MockHandlerStub([
            new Response(500, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);
        $licence = '0000000';
        $club = '0000000';

        try {
            $operation->retrieveJoueurDetails($licence, $club);
        } catch (\Exception $e) {
            $this->assertSame("Club '0000000' does not exist", $e->getMessage());
            $this->assertSame(ClubNotFoundException::class, $e::class);
        }
    }

    /**
     * Cas d'une liste de joueurs sans licence renseignée (chaïne vide) avec club existant.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsListeJoueurSansLicenceAvecClubExistant(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/RetrieveJoueurDetailsOperationTest/liste_joueurs_dans_club.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        /** @var array<JoueurDetails> $listeJoueurDetails */
        $listeJoueurDetails = $operation->retrieveJoueurDetails('', '08951331');

        $this->assertCount(82, $listeJoueurDetails);

        /** @var JoueurDetails $joueurDetails */
        $joueurDetails = $listeJoueurDetails[33];
        $this->assertSame(1342005, $joueurDetails->getIdLicence());
        $this->assertSame('9539821', $joueurDetails->getLicence());
        $this->assertSame('SERVANT', $joueurDetails->getNom());
        $this->assertSame('Elsa', $joueurDetails->getPrenom());
        $this->assertSame('08951331', $joueurDetails->getNumClub());
        $this->assertSame('LA FRETTE ESFTT', $joueurDetails->getNomClub());
        $this->assertFalse($joueurDetails->isHomme());
        $this->assertSame(TypeLicenceEnum::Loisir, $joueurDetails->getTypeLicence());
        $this->assertSame('15/09/2022', $joueurDetails->getDateValidation()?->format('d/m/Y'));
        $this->assertFalse($joueurDetails->isClasseNational());
        $this->assertSame('M1', $joueurDetails->getCategorie());
        $this->assertNull($joueurDetails->getClassementNational());
        $this->assertSame(500.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(500.0, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(500.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(500.0, $joueurDetails->getPointsLicence());
        $this->assertSame(NationaliteEnum::Française, $joueurDetails->getNationalite());
        $this->assertNull($joueurDetails->getDateMutation());
        $this->assertNull($joueurDetails->getDiplomeArbitre());
        $this->assertNull($joueurDetails->getDiplomeJugeArbitre());
        $this->assertNull($joueurDetails->getDiplomeTechnique());

        /** @var JoueurDetails $joueurDetails */
        $joueurDetails = $listeJoueurDetails[0];
        $this->assertSame(78392, $joueurDetails->getIdLicence());
        $this->assertSame('9511459', $joueurDetails->getLicence());
        $this->assertSame('DOUTRIAUX', $joueurDetails->getNom());
        $this->assertSame('Noel', $joueurDetails->getPrenom());
        $this->assertSame('08951331', $joueurDetails->getNumClub());
        $this->assertSame('LA FRETTE ESFTT', $joueurDetails->getNomClub());
        $this->assertFalse(!$joueurDetails->isHomme());
        $this->assertSame(TypeLicenceEnum::Decouverte, $joueurDetails->getTypeLicence());
        $this->assertSame('12/07/2022', $joueurDetails->getDateValidation()?->format('d/m/Y'));
        $this->assertFalse($joueurDetails->isClasseNational());
        $this->assertSame(null, $joueurDetails->getCategorie());
        $this->assertNull($joueurDetails->getClassementNational());
        $this->assertSame(970.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(965.5, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(975.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(970.0, $joueurDetails->getPointsLicence());
        $this->assertSame(null, $joueurDetails->getNationalite());
        $this->assertNull($joueurDetails->getDateMutation());
        $this->assertNull($joueurDetails->getDiplomeArbitre());
        $this->assertNull($joueurDetails->getDiplomeJugeArbitre());
        $this->assertNull($joueurDetails->getDiplomeTechnique());

        /** @var JoueurDetails $joueurDetails */
        $joueurDetails = $listeJoueurDetails[1];
        $this->assertSame(88226, $joueurDetails->getIdLicence());
        $this->assertSame('959179', $joueurDetails->getLicence());
        $this->assertSame('FANZUTTI', $joueurDetails->getNom());
        $this->assertSame('Patrick', $joueurDetails->getPrenom());
        $this->assertSame('08951331', $joueurDetails->getNumClub());
        $this->assertSame('LA FRETTE ESFTT', $joueurDetails->getNomClub());
        $this->assertFalse(!$joueurDetails->isHomme());
        $this->assertSame(TypeLicenceEnum::Evenementiel, $joueurDetails->getTypeLicence());
        $this->assertSame('06/07/2022', $joueurDetails->getDateValidation()?->format('d/m/Y'));
        $this->assertFalse($joueurDetails->isClasseNational());
        $this->assertSame('V3', $joueurDetails->getCategorie());
        $this->assertNull($joueurDetails->getClassementNational());
        $this->assertSame(577.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(570.0, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(564.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(577.0, $joueurDetails->getPointsLicence());
        $this->assertSame(null, $joueurDetails->getNationalite());
        $this->assertNull($joueurDetails->getDateMutation());
        $this->assertNull($joueurDetails->getDiplomeArbitre());
        $this->assertNull($joueurDetails->getDiplomeJugeArbitre());
        $this->assertNull($joueurDetails->getDiplomeTechnique());
    }

    /**
     * Cas d'un joueur non renseigné ni club renseigné.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsSansJoueurniClub(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/RetrieveJoueurDetailsOperationTest/missing_parameters.xml');
        $mock = new MockHandlerStub([
            new Response(400, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        try {
            $operation->retrieveJoueurDetails('', '');
        } catch (\Exception $e) {
            $this->assertTrue(str_contains($e->getMessage(), 'Status code 400 on URL "http://www.fftt.com/mobile/pxml/xml_licence_b.php'));
            $this->assertSame(InvalidRequestException::class, $e::class);
        }
    }

    /**
     * Cas d'un club sans joueurs.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsClubSansJoueurs(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/empty_result.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        $listeVideJoueurs = $operation->retrieveJoueurDetails('', '07021011');
        $this->assertSame([], $listeVideJoueurs);
    }
}
