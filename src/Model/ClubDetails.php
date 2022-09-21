<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class ClubDetails
{
    public function __construct(
        private readonly int $numero,
        private readonly string $nom,
        private readonly ?string $nomSalle,
        private readonly ?string $adresseSalle1,
        private readonly ?string $adresseSalle2,
        private readonly ?string $adresseSalle3,
        private readonly ?string $codePostaleSalle,
        private readonly ?string $villeSalle,
        private readonly ?string $siteWeb,
        private readonly ?string $nomCoordo,
        private readonly ?string $prenomCoordo,
        private readonly ?string $mailCoordo,
        private readonly ?string $telCoordo,
        private readonly ?float $latitude,
        private readonly ?float $longitude,
    ) {}

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getNomSalle(): ?string
    {
        return $this->nomSalle;
    }

    public function getAdresseSalle1(): ?string
    {
        return $this->adresseSalle1;
    }

    public function getAdresseSalle2(): ?string
    {
        return $this->adresseSalle2;
    }

    public function getAdresseSalle3(): ?string
    {
        return $this->adresseSalle3;
    }

    public function getCodePostaleSalle(): ?string
    {
        return $this->codePostaleSalle;
    }

    public function getVilleSalle(): ?string
    {
        return $this->villeSalle;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function getNomCoordo(): ?string
    {
        return $this->nomCoordo;
    }

    public function getPrenomCoordo(): ?string
    {
        return $this->prenomCoordo;
    }

    public function getMailCoordo(): ?string
    {
        return $this->mailCoordo;
    }

    public function getTelCoordo(): ?string
    {
        return $this->telCoordo;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
}
