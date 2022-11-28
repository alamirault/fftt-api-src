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
    }
}
