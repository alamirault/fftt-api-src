<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Joueur
{
    /**
     * @param int|null    $points  Points du joueur ou classement si classé dans les 1000 premiers français
     * @param string|null $echelon Egal à 'N' si classé dans les 1000 premiers français, sinon null
     * @param int|null    $place   Classement national si classé dans les 1000 premiers français
     */
    public function __construct(
        private readonly string $licence,
        private readonly string $clubId,
        private readonly string $club,
        private readonly string $nom,
        private readonly string $prenom,
        private readonly ?int $points,
        private readonly ?string $echelon = null,
        private readonly ?int $place = null,
    ) {}

    public function getLicence(): string
    {
        return $this->licence;
    }

    public function getClubId(): string
    {
        return $this->clubId;
    }

    public function getClub(): string
    {
        return $this->club;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function getEchelon(): ?string
    {
        return $this->echelon;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }
}
