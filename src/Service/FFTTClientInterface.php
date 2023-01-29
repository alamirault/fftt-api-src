<?php

namespace Alamirault\FFTTApi\Service;

use Alamirault\FFTTApi\Exception\InternalServerErrorException;
use Alamirault\FFTTApi\Exception\InvalidRequestException;
use Alamirault\FFTTApi\Exception\InvalidResponseException;

interface FFTTClientInterface
{
    /**
     * @param array<string, string> $params
     *
     * @return array<mixed>
     *
     * @throws InvalidRequestException
     * @throws InvalidResponseException
     * @throws InternalServerErrorException
     */
    public function get(string $path, array $params = [], string $queryParameter = null): array;
}
