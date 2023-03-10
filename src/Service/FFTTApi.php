<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service;

use Alamirault\FFTTApi\Model\Actualite;
use Alamirault\FFTTApi\Model\Classement;
use Alamirault\FFTTApi\Model\Club;
use Alamirault\FFTTApi\Model\ClubDetails;
use Alamirault\FFTTApi\Model\Enums\TypeEpreuveEnum;
use Alamirault\FFTTApi\Model\Epreuve;
use Alamirault\FFTTApi\Model\Equipe;
use Alamirault\FFTTApi\Model\EquipePoule;
use Alamirault\FFTTApi\Model\Factory\ClubFactory;
use Alamirault\FFTTApi\Model\Factory\RencontreDetailsFactory;
use Alamirault\FFTTApi\Model\Historique;
use Alamirault\FFTTApi\Model\Joueur;
use Alamirault\FFTTApi\Model\JoueurDetails;
use Alamirault\FFTTApi\Model\Organisme;
use Alamirault\FFTTApi\Model\Partie;
use Alamirault\FFTTApi\Model\Rencontre\Rencontre;
use Alamirault\FFTTApi\Model\Rencontre\RencontreDetails;
use Alamirault\FFTTApi\Model\UnvalidatedPartie;
use Alamirault\FFTTApi\Model\VirtualPoints;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListActualiteOperation;
use Alamirault\FFTTApi\Service\Operation\ListClubOperation;
use Alamirault\FFTTApi\Service\Operation\ListEpreuveOperation;
use Alamirault\FFTTApi\Service\Operation\ListEquipeOperation;
use Alamirault\FFTTApi\Service\Operation\ListEquipePouleOperation;
use Alamirault\FFTTApi\Service\Operation\ListHistoriqueOperation;
use Alamirault\FFTTApi\Service\Operation\ListJoueurOperation;
use Alamirault\FFTTApi\Service\Operation\ListOrganismeOperation;
use Alamirault\FFTTApi\Service\Operation\ListPartieOperation;
use Alamirault\FFTTApi\Service\Operation\ListRencontreOperation;
use Alamirault\FFTTApi\Service\Operation\RetrieveClassementOperation;
use Alamirault\FFTTApi\Service\Operation\RetrieveClubDetailsOperation;
use Alamirault\FFTTApi\Service\Operation\RetrieveJoueurDetailsOperation;
use Alamirault\FFTTApi\Service\Operation\RetrieveRencontreDetailsOperation;
use Alamirault\FFTTApi\Service\Operation\RetrieveVirtualPointsOperation;
use GuzzleHttp\Client;

/**
 * This class is not memory efficient but is easy to use
 * If you have a dependency injection system, inject operations instead.
 */
final class FFTTApi
{
    private ListOrganismeOperation $listOrganismeOperation;
    private ListClubOperation $listClubOperation;
    private RetrieveClubDetailsOperation $retrieveClubDetailsOperation;
    private ListJoueurOperation $listJoueurOperation;
    private RetrieveJoueurDetailsOperation $retrieveJoueurDetailsOperation;
    private RetrieveClassementOperation $retrieveClassementOperation;
    private ListHistoriqueOperation $listHistoriqueOperation;
    private ListPartieOperation $listPartieOperation;
    private RetrieveVirtualPointsOperation $virtualPointsOperation;
    private ListEquipeOperation $listEquipeOperation;
    private ListEquipePouleOperation $listEquipePouleOperation;
    private ListRencontreOperation $listRencontreOperation;
    private RetrieveRencontreDetailsOperation $retrieveRencontreDetailsOperation;
    private ListActualiteOperation $listActualiteOperation;
    private ListEpreuveOperation $listEpreuveOperation;

    public function __construct(string $id, string $password)
    {
        $arrayWrapper = new ArrayWrapper();

        $uriGenerator = new UriGenerator($id, $password);
        $FFTTClient = new FFTTClient(new Client(), $uriGenerator);

        $this->listOrganismeOperation = new ListOrganismeOperation($FFTTClient);

        $clubFactory = new ClubFactory();
        $this->listClubOperation = new ListClubOperation($FFTTClient, $clubFactory, $arrayWrapper);

        $nomPrenomExtractor = new NomPrenomExtractor();
        $this->retrieveClubDetailsOperation = new RetrieveClubDetailsOperation($FFTTClient, $this->listClubOperation);
        $this->listJoueurOperation = new ListJoueurOperation($FFTTClient, $arrayWrapper, $nomPrenomExtractor);
        $this->retrieveJoueurDetailsOperation = new RetrieveJoueurDetailsOperation($FFTTClient);
        $this->retrieveClassementOperation = new RetrieveClassementOperation($FFTTClient);
        $this->listHistoriqueOperation = new ListHistoriqueOperation($FFTTClient, $arrayWrapper);

        $this->listPartieOperation = new ListPartieOperation($FFTTClient, $arrayWrapper, $nomPrenomExtractor);

        $pointCalculator = new PointCalculator();
        $this->virtualPointsOperation = new RetrieveVirtualPointsOperation($this->retrieveClassementOperation, $this->listPartieOperation, $this->listJoueurOperation, $pointCalculator);

        $this->listEquipeOperation = new ListEquipeOperation($FFTTClient, $arrayWrapper);
        $this->listEquipePouleOperation = new ListEquipePouleOperation($FFTTClient);
        $this->listRencontreOperation = new ListRencontreOperation($FFTTClient);

        $rencontreDetailsFactory = new RencontreDetailsFactory($nomPrenomExtractor, $this->listJoueurOperation);
        $this->retrieveRencontreDetailsOperation = new RetrieveRencontreDetailsOperation($FFTTClient, $rencontreDetailsFactory);
        $this->listActualiteOperation = new ListActualiteOperation($FFTTClient, $arrayWrapper);
        $this->listEpreuveOperation = new ListEpreuveOperation($FFTTClient);
    }

    /**
     * @return array<Organisme>
     */
    public function listOrganismes(string $type = 'Z'): array
    {
        return $this->listOrganismeOperation->listOrganismes($type);
    }

    /**
     * @return array<Club>
     */
    public function listClubsByDepartement(int $departementId): array
    {
        return $this->listClubOperation->listClubsByDepartement($departementId);
    }

    /**
     * @return array<Club>
     */
    public function listClubsByName(string $name): array
    {
        return $this->listClubOperation->listClubsByName($name);
    }

    public function retrieveClubDetails(string $clubId): ClubDetails
    {
        return $this->retrieveClubDetailsOperation->retrieveClubDetails($clubId);
    }

    /**
     * @return array<Joueur>
     */
    public function listJoueursByClub(string $clubId): array
    {
        return $this->listJoueurOperation->listJoueursByClub($clubId);
    }

    /**
     * @return array<Joueur>
     */
    public function listJoueursByNom(string $nom, string $prenom = ''): array
    {
        return $this->listJoueurOperation->listJoueursByNom($nom, $prenom);
    }

    /**
     * @return JoueurDetails|array<JoueurDetails>
     */
    public function retrieveJoueurDetails(string $licenceId, ?string $clubId = null): JoueurDetails|array
    {
        return $this->retrieveJoueurDetailsOperation->retrieveJoueurDetails($licenceId, $clubId);
    }

    public function retrieveClassement(string $licenceId): Classement
    {
        return $this->retrieveClassementOperation->retrieveClassement($licenceId);
    }

    /**
     * @return array<Historique>
     */
    public function listHistorique(string $licenceId): array
    {
        return $this->listHistoriqueOperation->listHistorique($licenceId);
    }

    /**
     * @return array<Partie>
     */
    public function listPartiesJoueurByLicence(string $licenceId): array
    {
        return $this->listPartieOperation->listPartiesJoueurByLicence($licenceId);
    }

    /**
     * @return array<UnvalidatedPartie>
     */
    public function listUnvalidatedPartiesJoueurByLicence(string $licenceId): array
    {
        return $this->listPartieOperation->listUnvalidatedPartiesJoueurByLicence($licenceId);
    }

    public function retrieveVirtualPoints(string $licenceId): VirtualPoints
    {
        return $this->virtualPointsOperation->retrieveVirtualPoints($licenceId);
    }

    /**
     * @return array<Equipe>
     */
    public function listEquipesByClub(string $clubId, string $type = null): array
    {
        return $this->listEquipeOperation->listEquipesByClub($clubId, $type);
    }

    /**
     * @return array<EquipePoule>
     */
    public function listEquipePouleByLienDivision(string $lienDivision): array
    {
        return $this->listEquipePouleOperation->listEquipePouleByLienDivision($lienDivision);
    }

    /**
     * @return array<Rencontre>
     */
    public function listRencontrePouleByLienDivision(string $lienDivision): array
    {
        return $this->listRencontreOperation->listRencontrePouleByLienDivision($lienDivision);
    }

    /**
     * @return array<Rencontre>
     */
    public function listProchainesRencontresEquipe(Equipe $equipe): array
    {
        return $this->listRencontreOperation->listProchainesRencontresEquipe($equipe);
    }

    public function retrieveClubDetailsByEquipe(Equipe $equipe): ?ClubDetails
    {
        return $this->retrieveClubDetailsOperation->retrieveClubDetailsByEquipe($equipe);
    }

    public function retrieveRencontreDetailsByLien(string $lienRencontre, string $clubEquipeA = '', string $clubEquipeB = ''): RencontreDetails
    {
        return $this->retrieveRencontreDetailsOperation->retrieveRencontreDetailsByLien($lienRencontre, $clubEquipeA, $clubEquipeB);
    }

    /**
     * @return array<Actualite>
     */
    public function listActualites(): array
    {
        return $this->listActualiteOperation->listActualites();
    }

    /**
     * @return array<Epreuve>
     */
    public function listEpreuves(int $organisme, TypeEpreuveEnum $type = TypeEpreuveEnum::Equipe): array
    {
        return $this->listEpreuveOperation->listEpreuves($organisme, $type);
    }
}
