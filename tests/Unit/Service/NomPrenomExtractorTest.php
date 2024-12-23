<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service;

use Alamirault\FFTTApi\Service\NomPrenomExtractor;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\NomPrenomExtractor
 */
final class NomPrenomExtractorTest extends TestCase
{
    /**
     * @covers ::extractNomPrenom
     *
     * @dataProvider getData
     */
    public function testExtractNomPrenom(string $raw, string $expectedNom, string $expectedPrenom): void
    {
        $nomPrenomExtractor = new NomPrenomExtractor();

        [$nom, $prenom] = $nomPrenomExtractor->extractNomPrenom($raw);

        $this->assertSame($expectedNom, $nom);
        $this->assertSame($expectedPrenom, $prenom);
    }

    /**
     * @return \Generator<array<string>>
     */
    public function getData(): \Generator
    {
        yield [
            'MOREAU Véronique',
            'MOREAU',
            'Véronique',
        ];

        yield [
            'DA COSTA TEIXEIRA Ana',
            'DA COSTA TEIXEIRA',
            'Ana',
        ];

        yield [
            'AMOR   QUOINTEAU Erwan',
            'AMOR QUOINTEAU',
            'Erwan',
        ];

        yield [
            'GARBANI  - LECOURT Dimitri',
            'GARBANI-LECOURT',
            'Dimitri',
        ];

        yield [
            'GARBANI  - LECOURT NEVEU Dimitri-Sébastien',
            'GARBANI-LECOURT NEVEU',
            'Dimitri-Sébastien',
        ];

        yield [
            'GARBANI  - LECOURT NEVEU Dimitri - Sébastien',
            'GARBANI-LECOURT NEVEU',
            'Dimitri-Sébastien',
        ];

        yield [
            'AITÕ EL BACHA Yacine',
            'AITÕ EL BACHA',
            'Yacine',
        ];

        yield [
            'ÂBBÄS Ãbdel-Jælil',
            'ÂBBÄS',
            'Ãbdel-Jælil',
        ];

        yield [
            "DE L'EPREVIER Domitille",
            "DE L'EPREVIER",
            'Domitille',
        ];

        yield [
            'ÆYAO --  ÆYAOÈ Attien Henri',
            'ÆYAO-ÆYAOÈ',
            'Attien Henri',
        ];

        yield [
            'MARIÀNNI--SAMSON Mæl',
            'MARIÀNNI-SAMSON',
            'Mæl',
        ];

        yield [
            'NDJOM BASSANAGA Moïse Çéliñó',
            'NDJOM BASSANAGA',
            'Moïse Çéliñó',
        ];

        yield [
            "DE L  'EPREVIER Domitille",
            "DE L 'EPREVIER",
            'Domitille',
        ];

        yield [
            "DE L'EPREVIER PAVRON Domitille",
            "DE L'EPREVIER PAVRON",
            'Domitille',
        ];

        yield [
            "DE L'EPREVIER PAVRON-SEC Domìtïlle",
            "DE L'EPREVIER PAVRON-SEC",
            'Domìtïlle',
        ];

        yield [
            "DE L'EPREVIER PAVRON  - SEC Domitille",
            "DE L'EPREVIER PAVRON-SEC",
            'Domitille',
        ];

        yield [
            'SÉJOURNÉ Jérémy',
            'SÉJOURNÉ',
            'Jérémy',
        ];

        yield [
            'EVEN Gwec hen',
            'EVEN',
            'Gwec hen',
        ];
    }
}
