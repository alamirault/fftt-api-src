<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Historique
{
    public function __construct(
        private readonly int $anneeDebut,
        private readonly int $anneeFin,
        private readonly int $phase,
        private readonly int $points,
    ) {}

    public function getAnneeDebut(): int
    {
        return $this->anneeDebut;
    }

    public function getAnneeFin(): int
    {
        return $this->anneeFin;
    }

    public function getPhase(): int
    {
        return $this->phase;
    }

    public function getPoints(): int
    {
        return $this->points;
    }
}
