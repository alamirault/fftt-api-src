<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Alamirault\FFTTApi\Model\Club;
use Alamirault\FFTTApi\Model\Factory\ClubFactory;
use Alamirault\FFTTApi\Service\FFTTClientInterface;

final class ListClubOperation
{
    public function __construct(
        private readonly FFTTClientInterface $client,
        private readonly ClubFactory $clubFactory,
        private readonly ArrayWrapper $arrayWrapper,
    ) {}

    /**
     * @return array<Club>
     */
    public function listClubsByDepartement(int $departementId): array
    {
        /** @var array<array{numero: string, nom: string, validation: array<mixed>|string}> $rawClubs */
        $rawClubs = $this->client->get('xml_club_dep2', [
            'dep' => str_pad((string) $departementId, 2, '0', STR_PAD_LEFT),
        ])['club'] ?? [];

        return $this->clubFactory->createFromArray($rawClubs);
    }

    /**
     * @return array<Club>
     */
    public function listClubsByName(string $name): array
    {
        try {
            /** @var array<mixed> $rawClubs */
            $rawClubs = $this->client->get('xml_club_b', [
                'ville' => $name,
            ])['club'] ?? [];

            /** @var array<array{numero: string, nom: string, validation: array<mixed>|string}> $rawClubs */
            $rawClubs = $this->arrayWrapper->wrapArrayIfUnique($rawClubs);

            return $this->clubFactory->createFromArray($rawClubs);
        } catch (\Exception $e) {
            return [];
        }
    }
}
