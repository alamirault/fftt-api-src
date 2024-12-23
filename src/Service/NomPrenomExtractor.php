<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service;

final class NomPrenomExtractor implements NomPrenomExtractorInterface
{
    public function extractNomPrenom(string $raw): array
    {
        $raw = $this->removeSeparatorsDuplication($raw);
        // On extrait le nom et le prénom
        $return = preg_match("/^(?<nom>[A-ZÀ-Ý]+(?:(?:[\s'\-])*[A-ZÀ-Ý]+)*)\s(?<prenom>[A-ZÀ-Ý][a-zà-ÿ]*(?:(?:[\s'\-])*[A-ZÀ-Ý]?[a-zà-ÿ]*)*)$/", $raw, $result);

        return 1 !== $return ? ['', ''] :
        [
            $result['nom'],
            $result['prenom'],
        ];
    }

    /**
     * Permet de supprimer des séparateurs (espaces, tirets) dupliqués.
     */
    public function removeSeparatorsDuplication(string $raw): string
    {
        return preg_replace(['/\s+/', '/(?:\s*\-\s*)+|-+/'], [' ', '-'], $raw) ?? '';
    }
}
