<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model\Rencontre;

final class RencontreDetails
{
    /**
     * @param array<Joueur> $joueursA
     * @param array<Joueur> $joueursB
     * @param array<Partie> $parties
     */
    public function __construct(
        private readonly string $nomEquipeA,
        private readonly string $nomEquipeB,
        private readonly int $scoreEquipeA,
        private readonly int $scoreEquipeB,
        private readonly array $joueursA,
        private readonly array $joueursB,
        private readonly array $parties,
        private readonly float $expectedScoreEquipeA,
        private readonly float $expectedScoreEquipeB,
    ) {}

    public function getNomEquipeA(): string
    {
        return $this->nomEquipeA;
    }

    public function getNomEquipeB(): string
    {
        return $this->nomEquipeB;
    }

    public function getScoreEquipeA(): int
    {
        return $this->scoreEquipeA;
    }

    public function getScoreEquipeB(): int
    {
        return $this->scoreEquipeB;
    }

    /**
     * @return array<Joueur>
     */
    public function getJoueursA(): array
    {
        return $this->joueursA;
    }

    /**
     * @return array<Joueur>
     */
    public function getJoueursB(): array
    {
        return $this->joueursB;
    }

    /**
     * @return array<Partie>
     */
    public function getParties(): array
    {
        return $this->parties;
    }

    public function getExpectedScoreEquipeA(): float
    {
        return $this->expectedScoreEquipeA;
    }

    public function getExpectedScoreEquipeB(): float
    {
        return $this->expectedScoreEquipeB;
    }
}
