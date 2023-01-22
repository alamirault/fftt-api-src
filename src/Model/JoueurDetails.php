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
        private string|null $typeLicence,
        private readonly \DateTime|null $dateValidation,
        private readonly string $numClub,
        private readonly string $nomClub,
        private readonly bool $isHomme,
        private readonly string $categorie,
        // TODO: Créer une Enum pour convertir en libellés plus explicites
        private readonly float|null $pointDebutSaison,
        private readonly float $pointsLicence,
        private readonly float|null $pointsMensuel,
        private readonly float|null $pointsMensuelAnciens,
        private readonly bool $isClasseNational,
        private readonly int|null $classementNational,
        private string $nationalite,
        private readonly \DateTime|null $dateMutation,
        private readonly string|null $diplomeArbitre,
        private readonly string|null $diplomeJugeArbitre,
        private readonly string|null $diplomeTechnique,
    ) {
        $this->setNationalite();
        $this->setTypeLicence();
    }

    private function setTypeLicence(): void
    {
        switch ($this->typeLicence) {
            case TypeLicenceEnum::Traditionnelle->value:
                $this->typeLicence = 'Traditionnelle';
                break;
            case TypeLicenceEnum::Promotionnelle->value:
                $this->typeLicence = 'Promotionnelle';
                break;
            default:
                break;
        }
    }

    private function setNationalite(): void
    {
        switch ($this->nationalite) {
            case NationaliteEnum::Francaise->value:
                $this->nationalite = 'Nationalité française';
                break;
            case NationaliteEnum::Europeenne->value:
                $this->nationalite = 'Nationalité européenne';
                break;
            case NationaliteEnum::Etrangere->value:
                $this->nationalite = 'Nationalité étrangère';
                break;
            default:
                break;
        }
    }

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

    public function getNationalite(): string
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

    public function getTypeLicence(): string|null
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
