<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class JoueurDetails
{
    public function __construct(
        private readonly string $licence,
        private readonly string $nom,
        private readonly string $prenom,
        private readonly string $numClub,
        private readonly string $nomClub,
        private readonly bool $isHomme,
        private readonly string $categorie,
        private readonly float $pointDebutSaison,
        private readonly float $pointsLicence,
        private readonly float $pointsMensuel,
        private readonly float $pointsMensuelAnciens,
    ) {}

    public function getLicence(): string
    {
        return $this->licence;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNumClub(): string
    {
        return $this->numClub;
    }

    public function getNomClub(): string
    {
        return $this->nomClub;
    }

    public function isHomme(): bool
    {
        return $this->isHomme;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function getPointDebutSaison(): float
    {
        return $this->pointDebutSaison;
    }

    public function getPointsLicence(): float
    {
        return $this->pointsLicence;
    }

    public function getPointsMensuel(): float
    {
        return $this->pointsMensuel;
    }

    public function getPointsMensuelAnciens(): float
    {
        return $this->pointsMensuelAnciens;
    }
}
