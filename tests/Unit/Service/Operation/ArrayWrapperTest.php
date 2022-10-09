<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service\Operation;

use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ArrayWrapper
 */
final class ArrayWrapperTest extends TestCase
{
    /**
     * @covers ::wrapArrayIfUnique
     *
     * @dataProvider getData
     *
     * @param array<mixed>        $raw
     * @param array<array<mixed>> $expected
     */
    public function testWrapArrayIfUnique(array $raw, array $expected): void
    {
        $arrayWrapper = new ArrayWrapper();

        $result = $arrayWrapper->wrapArrayIfUnique($raw);
        $this->assertSame($expected, $result);
    }

    /**
     * @return Generator<array<mixed>>
     */
    public function getData(): Generator
    {
        yield 'Unique element' => [
            [
                'foo' => 'bar',
            ],
            [
                [
                    'foo' => 'bar',
                ],
            ],
        ];

        yield 'Multiple elements' => [
            [
                [
                    'foo' => 'bar',
                ],
            ],
            [
                [
                    'foo' => 'bar',
                ],
            ],
        ];

        yield 'No element' => [
            [],
            [
                [],
            ],
        ];
    }
}
