<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Exception;

use Alamirault\FFTTApi\Exception\InvalidResponseException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \FFTTApi\Exception\InvalidResponseException
 */
final class InvalidResponseExceptionTest extends TestCase
{
    /**
     * @covers ::generate
     */
    public function testConstruct(): void
    {
        $exception = new InvalidResponseException('foo', [
            'bar' => 'baz',
        ]);

        $this->assertSame('Invalid response on URL "foo", response "{"bar":"baz"}" given', $exception->getMessage());
    }
}
