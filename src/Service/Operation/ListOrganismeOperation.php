<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Alamirault\FFTTApi\Model\Organisme;
use Alamirault\FFTTApi\Service\FFTTClientInterface;

final class ListOrganismeOperation
{
    public function __construct(
        private readonly FFTTClientInterface $client,
    ) {}

    /**
     * @return array<Organisme>
     */
    public function listOrganismes(string $type = 'Z'): array
    {
        if (!in_array($type, ['Z', 'L', 'D'])) {
            $type = 'L';
        }

        /** @var array<array{libelle: string, id: string, code:string, idPere: string}> $organismes */
        $organismes = $this->client->get('xml_organisme', [
            'type' => $type,
        ])['organisme'];

        $result = [];
        foreach ($organismes as $organisme) {
            $result[] = new Organisme(
                $organisme['libelle'],
                (int) $organisme['id'],
                $organisme['code'],
                (int) $organisme['idPere']
            );
        }

        return $result;
    }
}
