<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Equipe
{
    public function __construct(
        private readonly string $libelle,
        private readonly string $division,
        private readonly string $lienDivision,
    ) {}

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getDivision(): string
    {
        return $this->division;
    }

    public function getLienDivision(): string
    {
        return $this->lienDivision;
    }
}
