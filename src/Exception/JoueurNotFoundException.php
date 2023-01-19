<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Exception;

final class JoueurNotFoundException extends \Exception
{
    public function __construct(string $licenceId, ?string $clubId = null)
    {
        $prefix = null != $clubId ? sprintf(" in club '%s'", $clubId) : null;
        parent::__construct(
            sprintf(
                "Joueur '%s' does not exist", $licenceId
            ).$prefix
        );
    }
}
