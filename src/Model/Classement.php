<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

final class Classement
{
    public function __construct(
        private readonly ?\DateTime $date,
        private readonly float $points,
        private readonly ?float $anciensPoints,
        private readonly int $classement,
        private readonly ?int $rangNational,
        private readonly ?int $rangRegional,
        private readonly ?int $rangDepartemental,
        private readonly ?int $pointsOfficiels,
        private readonly ?float $pointsInitials,
    ) {}

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function getAnciensPoints(): ?float
    {
        return $this->anciensPoints;
    }

    public function getClassement(): int
    {
        return $this->classement;
    }

    public function getRangNational(): ?int
    {
        return $this->rangNational;
    }

    public function getRangRegional(): ?int
    {
        return $this->rangRegional;
    }

    public function getRangDepartemental(): ?int
    {
        return $this->rangDepartemental;
    }

    public function getPointsOfficiels(): ?int
    {
        return $this->pointsOfficiels;
    }

    public function getPointsInitials(): ?float
    {
        return $this->pointsInitials;
    }
}
