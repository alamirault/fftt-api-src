<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Joueur
{
    /**
     * @param string|null $points Points du joueur ou classement si classé dans les 1000 premiers français
     */
    public function __construct(
        private readonly string $licence,
        private readonly string $clubId,
        private readonly string $club,
        private readonly string $nom,
        private readonly string $prenom,
        private readonly ?string $points,
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

    public function getPoints(): ?string
    {
        return $this->points;
    }
}
