<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Exception\ClubNotFoundException;
use Alamirault\FFTTApi\Exception\InvalidRequestException;
use Alamirault\FFTTApi\Exception\JoueurNotFoundException;
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
        $responseContent = '<?xml version="1.0" encoding="ISO-8859-1"?><liste><licence><idlicence>639188</idlicence><licence>3418930</licence><nom>LEBRUN</nom><prenom>Alexis</prenom><numclub>11340010</numclub><nomclub>MONTPELLIER TT</nomclub><sexe>M</sexe><type>T</type><certif>A</certif><validation>01/08/2022</validation><echelon>N</echelon><place>5</place><point>3508</point><cat>S</cat><pointm>28</pointm><apointm>3508</apointm><initm>3453</initm><mutation>21/02/2022</mutation><natio>F</natio><arb/><ja/><tech/></licence></liste>';
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        $joueurDetails = $operation->retrieveJoueurDetails('3418930');

        $this->assertSame(639188, $joueurDetails->getIdLicence());
        $this->assertSame('3418930', $joueurDetails->getLicence());
        $this->assertSame('LEBRUN', $joueurDetails->getNom());
        $this->assertSame('Alexis', $joueurDetails->getPrenom());
        $this->assertSame('11340010', $joueurDetails->getNumClub());
        $this->assertSame('MONTPELLIER TT', $joueurDetails->getNomClub());
        $this->assertSame(true, $joueurDetails->isHomme());
        $this->assertSame('Traditionnelle', $joueurDetails->getTypeLicence());
        $this->assertSame('01/08/2022', $joueurDetails->getDateValidation()->format('d/m/Y'));
        $this->assertSame(true, $joueurDetails->isClasseNational());
        $this->assertSame('S', $joueurDetails->getCategorie());
        $this->assertSame(5, $joueurDetails->getClassementNational());
        $this->assertSame(28.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(3508.0, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(3453.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(3508.0, $joueurDetails->getPointsLicence());
        $this->assertSame('Nationalité française', $joueurDetails->getNationalite());
        $this->assertSame('21/02/2022', $joueurDetails->getDateMutation()->format('d/m/Y'));
        $this->assertSame(null, $joueurDetails->getDiplomeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeJugeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeTechnique());
    }

    /**
     * Cas d'un joueur existant avec club existant.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurExistantAvecClub(): void
    {
        /** @var string $responseContent */
        $responseContent = '<?xml version="1.0" encoding="ISO-8859-1"?><liste><licence><idlicence>1370316</idlicence><licence>2221557</licence><nom>LE MORVAN</nom><prenom>Sébastien</prenom><numclub>08950978</numclub><nomclub>EAUBONNE CSM TT</nomclub><sexe>M</sexe><type>T</type><certif>A</certif><validation>18/09/2022</validation><echelon></echelon><place/><point>807</point><cat>V1</cat><pointm>807</pointm><apointm>775.25</apointm><initm>710.</initm><mutation>01/07/2022</mutation><natio>F</natio><arb/><ja/><tech/></licence></liste>';
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        $joueurDetails = $operation->retrieveJoueurDetails('2221557', '08950978');

        $this->assertSame(1370316, $joueurDetails->getIdLicence());
        $this->assertSame('2221557', $joueurDetails->getLicence());
        $this->assertSame('LE MORVAN', $joueurDetails->getNom());
        $this->assertSame('Sébastien', $joueurDetails->getPrenom());
        $this->assertSame('08950978', $joueurDetails->getNumClub());
        $this->assertSame('EAUBONNE CSM TT', $joueurDetails->getNomClub());
        $this->assertSame(true, $joueurDetails->isHomme());
        $this->assertSame('Traditionnelle', $joueurDetails->getTypeLicence());
        $this->assertSame('18/09/2022', $joueurDetails->getDateValidation()->format('d/m/Y'));
        $this->assertSame(false, $joueurDetails->isClasseNational());
        $this->assertSame('V1', $joueurDetails->getCategorie());
        $this->assertSame(null, $joueurDetails->getClassementNational());
        $this->assertSame(807.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(775.25, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(710.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(807.0, $joueurDetails->getPointsLicence());
        $this->assertSame('Nationalité française', $joueurDetails->getNationalite());
        $this->assertSame('01/07/2022', $joueurDetails->getDateMutation()->format('d/m/Y'));
        $this->assertSame(null, $joueurDetails->getDiplomeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeJugeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeTechnique());
    }

    /**
     * Cas d'un joueur non existant sans club.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsJoueurNonExistantSansClub(): void
    {
        /** @var string $responseContent */
        $responseContent = '<?xml version="1.0" encoding="ISO-8859-1"?><liste/>';
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
        $responseContent = '{"type":"https://tools.ietf.org/html/rfc2616#section-10","title":"An error occurred","detail":"Internal Server Error"}';
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
        $responseContent = '<?xml version="1.0" encoding="ISO-8859-1"?><liste/>';
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
        $club = '08951331';

        try {
            $operation->retrieveJoueurDetails($licence, $club);
        } catch (\Exception $e) {
            $this->assertSame("Joueur '0000000' does not exist in club '08951331'", $e->getMessage());
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
        $responseContent = '{"type":"https://tools.ietf.org/html/rfc2616#section-10","title":"An error occurred","detail":"Internal Server Error"}';
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
        $responseContent = file_get_contents(__DIR__.'/../fixtures/liste_joueurs_in_club_xml_licence_b.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        $listeJoueurDetails = $operation->retrieveJoueurDetails('', '08951331');

        $this->assertCount(82, $listeJoueurDetails);

        $joueurDetails = $listeJoueurDetails[33];

        $this->assertSame(1342005, $joueurDetails->getIdLicence());
        $this->assertSame('9539821', $joueurDetails->getLicence());
        $this->assertSame('SERVANT', $joueurDetails->getNom());
        $this->assertSame('Elsa', $joueurDetails->getPrenom());
        $this->assertSame('08951331', $joueurDetails->getNumClub());
        $this->assertSame('LA FRETTE ESFTT', $joueurDetails->getNomClub());
        $this->assertSame(false, $joueurDetails->isHomme());
        $this->assertSame('Promotionnelle', $joueurDetails->getTypeLicence());
        $this->assertSame('15/09/2022', $joueurDetails->getDateValidation()->format('d/m/Y'));
        $this->assertSame(false, $joueurDetails->isClasseNational());
        $this->assertSame('M1', $joueurDetails->getCategorie());
        $this->assertSame(null, $joueurDetails->getClassementNational());
        $this->assertSame(500.0, $joueurDetails->getPointsMensuel());
        $this->assertSame(500.0, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(500.0, $joueurDetails->getPointDebutSaison());
        $this->assertSame(500.0, $joueurDetails->getPointsLicence());
        $this->assertSame('Nationalité française', $joueurDetails->getNationalite());
        $this->assertSame(null, $joueurDetails->getDateMutation());
        $this->assertSame(null, $joueurDetails->getDiplomeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeJugeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeTechnique());
    }

    /**
     * Cas d'un joueur non renseigné ni club renseigné.
     *
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetailsSansJoueurniClub(): void
    {
        /** @var string $responseContent */
        $responseContent = '{"type":"https://tools.ietf.org/html/rfc2616#section-10","title":"An error occurred","detail":"Internal Server Error"}';
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
}
