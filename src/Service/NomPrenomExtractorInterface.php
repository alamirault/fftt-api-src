<?php

namespace Alamirault\FFTTApi\Service;

interface NomPrenomExtractorInterface
{
    /**
     * @return array{0: string, 1: string}
     */
    public function extractNomPrenom(string $raw): array;

    public function uniqueMultipleSpaces(string $raw): string;
}
