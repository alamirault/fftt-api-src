<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

use DateTime;

final class Club
{
    public function __construct(
        private readonly string $numero,
        private readonly string $nom,
        private readonly ?DateTime $dateValidation,
    ) {}

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getDateValidation(): ?DateTime
    {
        return $this->dateValidation;
    }
}
