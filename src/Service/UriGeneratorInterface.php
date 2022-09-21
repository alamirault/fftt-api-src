<?php

namespace Alamirault\FFTTApi\Service;

interface UriGeneratorInterface
{
    /**
     * @param array<string, string> $parameters
     */
    public function generate(string $path, array $parameters = [], string $queryParameter = null): string;
}
