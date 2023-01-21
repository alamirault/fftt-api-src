<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Alamirault\FFTTApi\Exception\ClubNotFoundException;
use Alamirault\FFTTApi\Exception\InternalServerErrorException;
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
    public function retrieveJoueurDetails(string $licenceId, ?string $clubId = null): JoueurDetails|array
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
        } catch (InternalServerErrorException) {
            throw new ClubNotFoundException($clubId);
        }

        if (array_key_exists('licence', $data)) {
            $data = $data['licence'];
        } else {
            throw new JoueurNotFoundException($licenceId, $clubId);
        }

        if (is_array(array_values($data)[0])) { // Une liste de joueurs est retournée si le paramètre "licence" est vide et que "club" est renseigné et existe
            $listeJoueurs = [];
            foreach ($data as $joueur) {
                $listeJoueurs[] = $this->returnJoueurDetails($joueur);
            }

            return $listeJoueurs;
        } else {
            return $this->returnJoueurDetails($data);
        }
    }

    private function returnJoueurDetails(array $joueurDetails): JoueurDetails
    {
        return new JoueurDetails(
            (int) $joueurDetails['idlicence'],
            $joueurDetails['licence'],
            $joueurDetails['nom'],
            $joueurDetails['prenom'],
            $joueurDetails['type'] ?: null,
            $joueurDetails['validation'] ? \DateTime::createFromFormat('!d/m/Y', $joueurDetails['validation']) : null,
            $joueurDetails['numclub'],
            $joueurDetails['nomclub'],
            self::TYPE_HOMME === $joueurDetails['sexe'] ? true : false,
            $joueurDetails['cat'],
            (float) ($joueurDetails['initm'] ?? (float) $joueurDetails['point']),
            (float) $joueurDetails['point'],
            (float) ($joueurDetails['pointm'] ?? (float) $joueurDetails['point']),
            (float) ($joueurDetails['apointm'] ?? (float) $joueurDetails['point']),
            self::TYPE_CLASSE_NATIONAL === $joueurDetails['echelon'] ? true : false,
            null != $joueurDetails['place'] ? (int) $joueurDetails['place'] : null,
            $joueurDetails['natio'],
            $joueurDetails['mutation'] ? \DateTime::createFromFormat('!d/m/Y', $joueurDetails['mutation']) : null,
            $joueurDetails['arb'] ?: null,
            $joueurDetails['ja'] ?: null,
            $joueurDetails['tech'] ?: null
        );
    }
}
