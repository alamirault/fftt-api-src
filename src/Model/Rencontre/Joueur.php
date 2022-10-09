<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model\Rencontre;

final class Joueur
{
    public function __construct(
        private readonly string $nom,
        private readonly string $prenom,
        private readonly string $licence,
        private readonly ?int $points,
        private readonly ?string $sexe,
    ) {}

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getLicence(): string
    {
        return $this->licence;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }
}
