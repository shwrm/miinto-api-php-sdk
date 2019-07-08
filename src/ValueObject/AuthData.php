<?php declare(strict_types=1);

namespace Shwrm\Miinto\ValueObject;

class AuthData
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $secret;

    /** @var string */
    private $apiUrl;

    public function __construct(string $apiUrl, string $identifier, string $secret)
    {
        $this->apiUrl     = $apiUrl;
        $this->identifier = $identifier;
        $this->secret     = $secret;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }
}
