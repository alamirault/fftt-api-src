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
        private string $typeLicence,
        private readonly \DateTime $dateValidation,
        private readonly string $numClub,
        private readonly string $nomClub,
        private readonly bool $isHomme,
        private readonly string $categorie, // TODO: Créer une Enum
        private readonly float $pointDebutSaison,
        private readonly float $pointsLicence,
        private readonly float $pointsMensuel,
        private readonly float $pointsMensuelAnciens,
        private readonly bool $isClasseNational,
        private readonly ?int $classementNational,
        private string $nationalite,
        private readonly ?\DateTime $dateMutation,
        private readonly ?string $diplomeArbitre,
        private readonly ?string $diplomeJugeArbitre,
        private readonly ?string $diplomeTechnique
    ) {
        // Définition de la nationalité
        switch ($this->nationalite) {
            case NationaliteEnum::Francaise->value:
                $this->nationalite = "Nationalité française";
                break;
            case NationaliteEnum::Europeenne->value:
                $this->nationalite = "Nationalité européenne";
                break;
            case NationaliteEnum::Etrangere->value:
                $this->nationalite = "Nationalité étrangère";
                break;
            default:
                break;
        }

        // Définition du type de licence
        switch ($this->typeLicence) {
            case TypeLicenceEnum::Competiteur->value:
                $this->typeLicence =  "Compétiteur";
                break;
            case TypeLicenceEnum::Loisir->value:
                $this->typeLicence =  "Loisir";
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

    public function getDateValidation(): \DateTime
    {
        return $this->dateValidation;
    }
    
    public function getDateMutation(): ?\DateTime
    {
        return $this->dateMutation;
    }
    
    public function getTypeLicence(): string
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
