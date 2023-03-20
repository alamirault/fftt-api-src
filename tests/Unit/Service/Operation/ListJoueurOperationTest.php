<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Model\Joueur;
use Alamirault\FFTTApi\Service\FFTTClient;
use Alamirault\FFTTApi\Service\NomPrenomExtractor;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListJoueurOperation;
use Alamirault\FFTTApi\Service\UriGenerator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListJoueurOperation
 */
final class ListJoueurOperationTest extends TestCase
{
    /**
     * @covers ::listJoueursByClub
     * This covers the route 'xml_liste_joueur_o'
     */
    public function testListJoueursByClub(): void
    {
        /** @var string $responseContent */
        $responseContent = file_get_contents(__DIR__.'/../fixtures/ListJoueurOperationTest/liste_joueurs_xml_liste_joueur_o.xml');
        $mock = new MockHandlerStub([
            new Response(200, [
                'content-type' => ['text/html; charset=UTF-8'],
            ], $responseContent),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $FFTTClient = new FFTTClient($client, new UriGenerator('foo', 'bar'));

        $operation = new ListJoueurOperation($FFTTClient, new ArrayWrapper(), new NomPrenomExtractor());

        $result = $operation->listJoueursByClub('05650412');

        $this->assertCount(7, $result);
        $this->assertEquals(new Joueur('9521866', 'CERGY PONTOISE', '05650412', 'HERNANDEZ', 'Francois', 816, null, null), $result[0]);
        $this->assertEquals(new Joueur('9521899', 'CERGY PONTOISE', '05650412', "DE L'EPREVIER PAVRON-SEC", 'Domìtïlle', 812, null, null), $result[1]);
        $this->assertEquals(new Joueur('9521877', 'CERGY PONTOISE', '05650412', 'NDJOM BASSANAGA', 'Moïse Çéliñó', 1216, null, null), $result[2]);
        $this->assertEquals(new Joueur('95218367', 'CERGY PONTOISE', '05650412', 'ÂBBÄS', 'Ãbdel-Jælil', 1456, 'N', 924), $result[3]);
        $this->assertEquals(new Joueur('9521870', 'CERGY PONTOISE', '05650412', 'GARBANI-LECOURT NEVEU', 'Dimitri-Sébastien', 1456, null, null), $result[4]);
        $this->assertEquals(new Joueur('9521876', 'CERGY PONTOISE', '05650412', 'AMOR QUOINTEAU', 'Erwan', 756, null, null), $result[5]);
        $this->assertEquals(new Joueur('9521888', 'CERGY PONTOISE', '05650412', 'MOREAU', 'Véronique', 1956, 'N', 54), $result[6]);
    }
}
