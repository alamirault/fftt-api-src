<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model\Rencontre;

use DateTime;

final class Rencontre
{
    public function __construct(
        private readonly string $libelle,
        private readonly string $nomEquipeA,
        private readonly string $nomEquipeB,
        private readonly int $scoreEquipeA,
        private readonly int $scoreEquipeB,
        private readonly string $lien,
        private readonly DateTime $datePrevue,
        private readonly ?DateTime $dateReelle,
    ) {}

    public function getLibelle(): string
    {
        return $this->libelle;
    }

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

    public function getLien(): string
    {
        return $this->lien;
    }

    public function getDatePrevue(): DateTime
    {
        return $this->datePrevue;
    }

    public function getDateReelle(): ?DateTime
    {
        return $this->dateReelle;
    }
}
