<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Alamirault\FFTTApi\Model\Epreuve;
use Alamirault\FFTTApi\Service\FFTTClientInterface;

final class ListEpreuveOperation
{
    public function __construct(
        private readonly FFTTClientInterface $client,
    ) {}

    /**
     * @param string $type (E : épreuves par équipe, I: épreuves individuelles)
     *
     * @return array<Epreuve>
     */
    public function listEpreuves(string $type, int $organisme): array
    {
        /**
         * E : épreuves par équipe, I: épreuves individuelles.
         */
        if (!in_array($type, ['E', 'I'])) {
            $type = 'I';
        }

        /** @var array<array{idepreuve: string, idorga: string, libelle:string, typepreuve: string}> $epreuves */
        $epreuves = $this->client->get('xml_epreuve', [
            'type' => $type,
            'organisme' => $organisme,
        ])['epreuve'];

        $result = [];
        foreach ($epreuves as $epreuve) {
            $result[] = new Epreuve(
                (int) $epreuve['idepreuve'],
                (int) $epreuve['idorga'],
                $epreuve['libelle'],
                $epreuve['typepreuve']
            );
        }

        return $result;
    }
}
