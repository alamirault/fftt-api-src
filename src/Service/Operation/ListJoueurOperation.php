<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Accentuation\Accentuation;
use Alamirault\FFTTApi\Exception\ClubNotFoundException;
use Alamirault\FFTTApi\Exception\InvalidResponseException;
use Alamirault\FFTTApi\Model\Joueur;
use Alamirault\FFTTApi\Service\FFTTClientInterface;

final class ListJoueurOperation
{
    public function __construct(
        private readonly FFTTClientInterface $client,
        private readonly ArrayWrapper $arrayWrapper,
    ) {}

    /**
     * @return array<Joueur>
     */
    public function listJoueursByClub(string $clubId): array
    {
        try {
            $arrayJoueurs = $this->client->get('xml_liste_joueur_o', [
                'club' => $clubId, 'valid' => 1, 
            ]
            );
        } catch (InvalidResponseException) {
            throw new ClubNotFoundException($clubId);
        }

        $result = [];

        foreach ($arrayJoueurs['joueur'] ?? [] as $joueur) {
            $realJoueur = new Joueur(
                $joueur['licence'],
                $joueur['nclub'],
                $joueur['club'],
                $joueur['nom'],
                $joueur['prenom'],
                $joueur['points']);
            $result[] = $realJoueur;
        }

        return $result;
    }

    /**
     * @return array<Joueur>
     */
    public function listJoueursByNom(string $nom, string $prenom = ''): array
    {
        /** @var array<mixed> $arrayJoueurs */
        $arrayJoueurs = $this->client->get('xml_liste_joueur', [
            'nom' => addslashes(Accentuation::remove($nom)),
            'prenom' => addslashes(Accentuation::remove($prenom)),
        ]
        )['joueur'];

        $arrayJoueurs = $this->arrayWrapper->wrapArrayIfUnique($arrayJoueurs);

        $result = [];

        /** @var array{licence: string, nclub: string, club: string, nom: string, prenom: string, clast: string|null} $joueur */
        foreach ($arrayJoueurs as $joueur) {
            $realJoueur = new Joueur(
                $joueur['licence'],
                $joueur['nclub'],
                $joueur['club'],
                $joueur['nom'],
                $joueur['prenom'],
                $joueur['clast']);
            $result[] = $realJoueur;
        }

        return $result;
    }
}
