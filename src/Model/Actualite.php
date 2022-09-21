<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Actualite
{
    public function __construct(
        private readonly \DateTime $date,
        private readonly string $titre,
        private readonly string $description,
        private readonly string $url,
        private readonly string $photo,
        private readonly string $categorie,
    ) {}

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }
}
