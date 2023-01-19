<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Alamirault\FFTTApi\Exception\ClubNotFoundException;
use Alamirault\FFTTApi\Exception\JoueurNotFoundException;
use Alamirault\FFTTApi\Model\JoueurDetails;
use Alamirault\FFTTApi\Service\FFTTClientInterface;


final class RetrieveJoueurDetailsOperation
{
    public const TYPE_HOMME = 'M';
    public const TYPE_CLASSE_NATIONAL = 'N';

    public function __construct(
        private readonly FFTTClientInterface $client,
    ) {}

    /**
     * Retrieve list of players searched by licenceId, filtered by a specific club with an optionnal clubId.
     *
     * @throws JoueurNotFoundException
     */
    public function retrieveJoueurDetails(string $licenceId, ?string $clubId = null): JoueurDetails
    {
        $options = [
            'licence' => $licenceId,
        ];

        if (null != $clubId) {
            $options['club'] = $clubId;
        }

        try {
            /** @var array<mixed> $data */
            $data = $this->client->get('xml_licence_b', $options);
        } catch (\Exception $e) {
            throw new ClubNotFoundException($clubId);
        }

        if (array_key_exists('licence', $data)) {
            $data = $data['licence'];
        } else {
            throw new JoueurNotFoundException($licenceId, $clubId);
        }

        $joueurDetails = new JoueurDetails(
            (int) $data['idlicence'],
            $licenceId,
            $data['nom'],
            $data['prenom'],
            $data['type'],
            \DateTime::createFromFormat('!d/m/Y', $data['validation']),
            $data['numclub'],
            $data['nomclub'],
            self::TYPE_HOMME === $data['sexe'] ? true : false,
            $data['cat'],
            (float) ($data['initm'] ?? (float) $data['point']),
            (float) $data['point'],
            (float) ($data['pointm'] ?? (float) $data['point']),
            (float) ($data['apointm'] ?? (float) $data['point']),
            self::TYPE_CLASSE_NATIONAL === $data['echelon'] ? true : false,
            $data['place'] != null ? (int) $data['place'] : null,
            $data['natio'],
            $data['mutation'] ? \DateTime::createFromFormat('!d/m/Y', $data['validation']) : null,
            $data['arb'] ?: null,
            $data['ja'] ?: null,
            $data['tech'] ?: null
        );

        return $joueurDetails;
    }
}
