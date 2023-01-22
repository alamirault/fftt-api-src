<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

use Alamirault\FFTTApi\Model\Enums\NationaliteEnum;
use Alamirault\FFTTApi\Model\Enums\TypeLicenceEnum;

final class JoueurDetails
{
    public function __construct(
        private readonly int $idLicence,
        private readonly string $licence,
        private readonly string $nom,
        private readonly string $prenom,
        private readonly TypeLicenceEnum|null $typeLicence,
        private readonly \DateTime|null $dateValidation,
        private readonly string $numClub,
        private readonly string $nomClub,
        private readonly bool $isHomme,
        private readonly string $categorie,
        // TODO: Créer une Enum pour convertir "categorie" en libellés plus explicites
        private readonly float|null $pointDebutSaison,
        private readonly float $pointsLicence,
        private readonly float|null $pointsMensuel,
        private readonly float|null $pointsMensuelAnciens,
        private readonly bool $isClasseNational,
        private readonly int|null $classementNational,
        private readonly NationaliteEnum $nationalite,
        private readonly \DateTime|null $dateMutation,
        private readonly string|null $diplomeArbitre,
        private readonly string|null $diplomeJugeArbitre,
        private readonly string|null $diplomeTechnique,
    ) {}

    public function getIdLicence(): int
    {
        return $this->idLicence;
    }

    public function getDiplomeArbitre(): ?string
    {
        return $this->diplomeArbitre;
    }

    public function getDiplomeJugeArbitre(): ?string
    {
        return $this->diplomeJugeArbitre;
    }

    public function getDiplomeTechnique(): ?string
    {
        return $this->diplomeTechnique;
    }

    public function getClassementNational(): ?int
    {
        return $this->classementNational;
    }

    public function getNationalite(): NationaliteEnum
    {
        return $this->nationalite;
    }

    public function getDateValidation(): \DateTime|null
    {
        return $this->dateValidation;
    }

    public function getDateMutation(): \DateTime|null
    {
        return $this->dateMutation;
    }

    public function getTypeLicence(): TypeLicenceEnum|null
    {
        return $this->typeLicence;
    }

    public function isClasseNational(): bool
    {
        return $this->isClasseNational;
    }

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

    public function getPointDebutSaison(): float|null
    {
        return $this->pointDebutSaison;
    }

    public function getPointsLicence(): float
    {
        return $this->pointsLicence;
    }

    public function getPointsMensuel(): float|null
    {
        return $this->pointsMensuel;
    }

    public function getPointsMensuelAnciens(): float|null
    {
        return $this->pointsMensuelAnciens;
    }
}
