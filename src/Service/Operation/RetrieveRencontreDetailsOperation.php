<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service\Operation;

use Alamirault\FFTTApi\Exception\InvalidLienRencontreException;
use Alamirault\FFTTApi\Model\Factory\RencontreDetailsFactory;
use Alamirault\FFTTApi\Model\Rencontre\RencontreDetails;
use Alamirault\FFTTApi\Service\FFTTClientInterface;

final class RetrieveRencontreDetailsOperation
{
    public function __construct(
        private readonly FFTTClientInterface $client,
        private readonly RencontreDetailsFactory $rencontreDetailsFactory,
    ) {}

    public function retrieveRencontreDetailsByLien(string $lienRencontre, string $clubEquipeA = '', string $clubEquipeB = ''): RencontreDetails
    {
        $data = $this->client->get('xml_chp_renc', [], $lienRencontre);
        if (!(isset($data['resultat']) && isset($data['joueur']) && isset($data['partie']))) {
            throw new InvalidLienRencontreException($lienRencontre);
        }

        return $this->rencontreDetailsFactory->createFromArray($data, $clubEquipeA, $clubEquipeB);
    }
}
