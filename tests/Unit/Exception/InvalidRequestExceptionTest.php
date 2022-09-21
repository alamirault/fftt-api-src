<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Exception;

use Alamirault\FFTTApi\Exception\InvalidRequestException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \FFTTApi\Exception\InvalidRequestException
 */
final class InvalidRequestExceptionTest extends TestCase
{
    /**
     * @covers ::generate
     */
    public function testConstruct(): void
    {
        $xml = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<user>
    <verification>0</verification>
    <erreur>Compte incorrect</erreur>
</user>
XML;

        $exception = new InvalidRequestException('foo', 401, $xml);

        $this->assertSame('Status code 401 on URL "foo", response "Compte incorrect" given', $exception->getMessage());
    }

    /**
     * @covers ::generate
     */
    public function testConstructWithPlainText(): void
    {
        $exception = new InvalidRequestException('foo', 401, 'Not an xml response');

        $this->assertSame('Status code 401 on URL "foo", response "Not an xml response" given', $exception->getMessage());
    }
}
