<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
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
     * Cas d'un joueur existant sans clubId
     * @covers ::retrieveJoueurDetails
     */
    public function testRetrieveJoueurDetails(): void
    {
        /** @var string $responseContent */
        $responseContent = `<?xml version="1.0" encoding="ISO-8859-1"?><liste><licence><idlicence>639188</idlicence><licence>3418930</licence><nom>LEBRUN</nom><prenom>Alexis</prenom><numclub>11340010</numclub><nomclub>MONTPELLIER TT</nomclub><sexe>M</sexe><type>T</type><certif>A</certif><validation>08/08/2022</validation><echelon>N</echelon><place>5</place><point>3508</point><cat>S</cat><pointm>28</pointm><apointm>3508</apointm><initm>3453</initm><mutation>21/02/2022</mutation><natio>F</natio><arb/><ja/><tech/></licence></liste>`;
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new RetrieveJoueurDetailsOperation($FFTTClient);

        $licence = '3418930';
        $joueurDetails = $operation->retrieveJoueurDetails($licence);

        $this->assertSame('639188', $joueurDetails->getIdLicence());
        $this->assertSame('3418930', $licence);
        $this->assertSame('LEBRUN', $joueurDetails->getNom());
        $this->assertSame('Alexis', $joueurDetails->getPrenom());
        $this->assertSame('11340010', $joueurDetails->getNumClub());
        $this->assertSame('MONTPELLIER TT', $joueurDetails->getNomClub());
        $this->assertSame(true, $joueurDetails->isHomme());
        $this->assertSame("Compétiteur", $joueurDetails->getTypeLicence());
        $this->assertSame(\DateTime::createFromFormat('!d/m/Y', '22/11/2022'), $joueurDetails->getDateValidation());
        $this->assertSame(true, $joueurDetails->isClasseNational());
        $this->assertSame(5, $joueurDetails->getClassementNational());
        $this->assertSame(3508, $joueurDetails->getPointsMensuel());
        $this->assertSame(3508, $joueurDetails->getPointsMensuelAnciens());
        $this->assertSame(3453, $joueurDetails->getPointDebutSaison());
        $this->assertSame("Nationalité française", $joueurDetails->getNationalite());
        $this->assertSame(\DateTime::createFromFormat('!d/m/Y', '21/02/2022'), $joueurDetails->getDateMutation());
        $this->assertSame(null, $joueurDetails->getDiplomeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeJugeArbitre());
        $this->assertSame(null, $joueurDetails->getDiplomeTechnique());
    }
}
