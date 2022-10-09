<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class EquipePoule
{
    public function __construct(
        private readonly int $classement,
        private readonly string $nomEquipe,
        private readonly int $matchJouees,
        private readonly int $points,
        private readonly string $numero,
        private readonly int $victoires,
        private readonly int $defaites,
        private readonly int $idEquipe,
        private readonly string $idCLub,
    ) {}

    public function getClassement(): int
    {
        return $this->classement;
    }

    public function getNomEquipe(): string
    {
        return $this->nomEquipe;
    }

    public function getMatchJouees(): int
    {
        return $this->matchJouees;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getVictoires(): int
    {
        return $this->victoires;
    }

    public function getDefaites(): int
    {
        return $this->defaites;
    }

    public function getIdEquipe(): int
    {
        return $this->idEquipe;
    }

    public function getIdCLub(): string
    {
        return $this->idCLub;
    }
}
