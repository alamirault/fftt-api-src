<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model\Rencontre;

final class Partie
{
    /**
     * @param array<string> $setDetails
     */
    public function __construct(
        private readonly string $adversaireA,
        private readonly string $adversaireB,
        private readonly int $scoreA,
        private readonly int $scoreB,
        private readonly array $setDetails,
    ) {}

    public function getAdversaireA(): string
    {
        return $this->adversaireA;
    }

    public function getAdversaireB(): string
    {
        return $this->adversaireB;
    }

    public function getScoreA(): int
    {
        return $this->scoreA;
    }

    public function getScoreB(): int
    {
        return $this->scoreB;
    }

    /**
     * @return array<string>
     */
    public function getSetDetails(): array
    {
        return $this->setDetails;
    }
}
