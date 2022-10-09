<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Tests\Integration\Service\Operation;

use Alamirault\FFTTApi\Model\Factory\ClubFactory;
use Alamirault\FFTTApi\Service\Operation\ArrayWrapper;
use Alamirault\FFTTApi\Service\Operation\ListClubOperation;
use Alamirault\FFTTApi\Tests\Integration\AbstractIntegrationTestCase;

/**
 * @coversDefaultClass \Alamirault\FFTTApi\Service\Operation\ListClubOperation
 */
final class ListClubOperationTest extends AbstractIntegrationTestCase
{
    /**
     * @coversNothing
     */
    public function testListActualites(): void
    {
        $FFTTClient = $this->getFFTTClient();

        $operation = new ListClubOperation($FFTTClient, new ClubFactory(), new ArrayWrapper());

        $result = $operation->listClubsByDepartement(37);

        $this->assertNotEmpty($result);
    }
}
