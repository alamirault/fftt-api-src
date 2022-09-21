<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Model;

class VirtualPoints
{
    public function __construct(
        private readonly float $monthlyPointsWon,
        private readonly float $virtualPoints,
        private readonly float $seasonlyPointsWon,
    ) {}

    public function getMonthlyPointsWon(): float
    {
        return $this->monthlyPointsWon;
    }

    public function getVirtualPoints(): float
    {
        return $this->virtualPoints;
    }

    public function getSeasonlyPointsWon(): float
    {
        return $this->seasonlyPointsWon;
    }
}
