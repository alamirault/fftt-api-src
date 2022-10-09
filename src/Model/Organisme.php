<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Organisme
{
    public function __construct(
        private readonly string $libelle,
        private readonly int $id,
        private readonly string $code,
        private readonly int $idPere,
    ) {}

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getIdPere(): int
    {
        return $this->idPere;
    }
}
