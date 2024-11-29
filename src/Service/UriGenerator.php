<?php declare(strict_types=1);

namespace Alamirault\FFTTApi\Service;

final class UriGenerator implements UriGeneratorInterface
{
    private const FFTT_URL = 'https://www.fftt.com/mobile/pxml/';

    public function __construct(
        private readonly string $id,
        private readonly string $password,
    ) {}

    public function generate(string $path, array $parameters = [], ?string $queryParameter = null): string
    {
        $time = round(microtime(true) * 1000);
        $hashedKey = hash_hmac('sha1', (string) $time, md5($this->password));

        $uri = self::FFTT_URL.$path.'.php?serie='.$this->id.'&tm='.$time.'&tmc='.$hashedKey.'&id='.$this->id;

        if ($queryParameter) {
            $uri .= '&'.$queryParameter;
        }

        foreach ($parameters as $key => $value) {
            $uri .= '&'.$key.'='.$value;
        }

        return $uri;
    }
}
