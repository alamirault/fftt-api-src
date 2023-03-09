<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service;

final class NomPrenomExtractor implements NomPrenomExtractorInterface
{
    public function extractNomPrenom(string $raw): array
    {
        $patterns = ['/\s+/', '/-+/'];
        $replacements = [' ', '-'];
        // On remplace les N espaces et tirets d'affilée
        $raw = preg_replace($patterns, $replacements, $raw) ?? '';
        // On extrait le nom et le prénom
        $return = preg_match("/^(?<nom>[A-ZÀ-Ý]+(?:(?:[\s'\-])*[A-Z]+)*)\s(?<prenom>[A-ZÀ-Ý][a-zà-ÿ]*(?:(?:[\s'\-])*[A-ZÀ-Ý][a-zà-ÿ]*)*)$/", $raw, $result);

        return 1 !== $return ? ['', ''] :
        [
            $result['nom'],
            $result['prenom'],
        ];
    }
}
