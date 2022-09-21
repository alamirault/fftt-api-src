<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Partie
{
    public function __construct(
        private readonly bool $isVictoire,
        private readonly int $journee,
        private readonly \DateTime $date,
        private readonly float $pointsObtenus,
        private readonly float $coefficient,
        private readonly string $adversaireLicence,
        private readonly bool $adversaireIsHomme,
        private readonly string $adversaireNom,
        private readonly string $adversairePrenom,
        private readonly int $adversaireClassement,
    ) {}

    public function isVictoire(): bool
    {
        return $this->isVictoire;
    }

    public function getJournee(): int
    {
        return $this->journee;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getPointsObtenus(): float
    {
        return $this->pointsObtenus;
    }

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }

    public function getAdversaireLicence(): string
    {
        return $this->adversaireLicence;
    }

    public function isAdversaireIsHomme(): bool
    {
        return $this->adversaireIsHomme;
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
