<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Unit\Service;

use Alamirault\FFTTApi\Service\UriGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\UriGenerator
 */
final class UriGeneratorTest extends TestCase
{
    /**
     * @covers ::generate
     */
    public function testGenerate(): void
    {
        $uriGenerator = new UriGenerator('foo', 'bar');

        $uri = $uriGenerator->generate('baz');

        /** @var array<string, string> $uriParts */
        $uriParts = parse_url($uri);

        $this->assertSame('http', $uriParts['scheme']);
        $this->assertSame('www.fftt.com', $uriParts['host']);
        $this->assertSame('/mobile/pxml/baz.php', $uriParts['path']);

        $queryParts = explode('&', $uriParts['query']);

        $this->assertCount(4, $queryParts);
        $this->assertSame('serie=foo', $queryParts[0]);
        $this->assertStringStartsWith('tm=', $queryParts[1]);
        $this->assertStringStartsWith('tmc=', $queryParts[2]);
        $this->assertSame('id=foo', $queryParts[3]);
    }

    /**
     * @covers ::generate
     */
    public function testGenerateWithParameters(): void
    {
        $uriGenerator = new UriGenerator('foo', 'bar');

        $uri = $uriGenerator->generate('baz', [
            'param-1' => 'value-1',
            'param-2' => 'value-2',
        ]);

        /** @var array<string, string> $uriParts */
        $uriParts = parse_url($uri);

        $this->assertSame('http', $uriParts['scheme']);
        $this->assertSame('www.fftt.com', $uriParts['host']);
        $this->assertSame('/mobile/pxml/baz.php', $uriParts['path']);

        $queryParts = explode('&', $uriParts['query']);

        $this->assertCount(6, $queryParts);
        $this->assertSame('serie=foo', $queryParts[0]);
        $this->assertStringStartsWith('tm=', $queryParts[1]);
        $this->assertStringStartsWith('tmc=', $queryParts[2]);
        $this->assertSame('id=foo', $queryParts[3]);
        $this->assertSame('param-1=value-1', $queryParts[4]);
        $this->assertSame('param-2=value-2', $queryParts[5]);
    }

    /**
     * @covers ::generate
     */
    public function testGenerateWithQueryParameter(): void
    {
        $uriGenerator = new UriGenerator('foo', 'bar');

        $uri = $uriGenerator->generate('baz', [
            'param-1' => 'value-1',
            'param-2' => 'value-2',
        ], 'param-3=value-3');

        /** @var array<string, string> $uriParts */
        $uriParts = parse_url($uri);

        $this->assertSame('http', $uriParts['scheme']);
        $this->assertSame('www.fftt.com', $uriParts['host']);
        $this->assertSame('/mobile/pxml/baz.php', $uriParts['path']);

        $queryParts = explode('&', $uriParts['query']);

        $this->assertCount(7, $queryParts);
        $this->assertSame('serie=foo', $queryParts[0]);
        $this->assertStringStartsWith('tm=', $queryParts[1]);
        $this->assertStringStartsWith('tmc=', $queryParts[2]);
        $this->assertSame('id=foo', $queryParts[3]);
        $this->assertSame('param-3=value-3', $queryParts[4]);
        $this->assertSame('param-1=value-1', $queryParts[5]);
        $this->assertSame('param-2=value-2', $queryParts[6]);
    }
}
