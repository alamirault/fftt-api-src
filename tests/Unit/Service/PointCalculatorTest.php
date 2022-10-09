<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service;

use Alamirault\FFTTApi\Service\PointCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\PointCalculator
 */
final class PointCalculatorTest extends TestCase
{
    /**
     * @covers ::getPointDefeat
     */
    public function testGetPointDefeat(): void
    {
        $pointCalculator = new PointCalculator();

        $this->assertSame(-8.0, $pointCalculator->getPointDefeat(930, 800));
        $this->assertSame(-3.0, $pointCalculator->getPointDefeat(800, 930));
        $this->assertSame(-29.0, $pointCalculator->getPointDefeat(1500, 500));
        $this->assertSame(0.0, $pointCalculator->getPointDefeat(500, 1500));
    }

    /**
     * @covers ::getPointVictory
     */
    public function testGetPointVictory(): void
    {
        $pointCalculator = new PointCalculator();

        $this->assertSame(4.0, $pointCalculator->getPointVictory(930, 800));
        $this->assertSame(10.0, $pointCalculator->getPointVictory(800, 930));
        $this->assertSame(0.0, $pointCalculator->getPointVictory(1500, 500));
        $this->assertSame(40.0, $pointCalculator->getPointVictory(500, 1500));
    }
}
