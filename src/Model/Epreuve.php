<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Epreuve
{
    public function __construct(
        private readonly int $idEpreuve,
        private readonly int $idOrga,
        private readonly string $libelle,
        private readonly string $typeEpreuve,
    ) {}

    public function getIdEpreuve(): int
    {
        return $this->idEpreuve;
    }

    public function getIdOrga(): int
    {
        return $this->idOrga;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getTypeEpreuve(): string
    {
        return $this->typeEpreuve;
    }
}
