<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class UnvalidatedPartie
{
    public function __construct(
        private readonly string $epreuve,
        private readonly string $idPartie,
        private readonly float $coefficientChampionnat,
        private readonly bool $isVictoire,
        private readonly bool $isForfait,
        private readonly \DateTime $date,
        private readonly string $adversaireNom,
        private readonly string $adversairePrenom,
        private readonly int $adversaireClassement,
    ) {}

    public function getEpreuve(): string
    {
        return $this->epreuve;
    }

    public function getIdPartie(): string
    {
        return $this->idPartie;
    }

    public function getCoefficientChampionnat(): float
    {
        return $this->coefficientChampionnat;
    }

    public function isVictoire(): bool
    {
        return $this->isVictoire;
    }

    public function isForfait(): bool
    {
        return $this->isForfait;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getAdversaireNom(): string
    {
        return $this->adversaireNom;
    }

    public function getAdversairePrenom(): string
    {
        return $this->adversairePrenom;
    }

    public function getAdversaireClassement(): int
    {
        return $this->adversaireClassement;
    }
}
