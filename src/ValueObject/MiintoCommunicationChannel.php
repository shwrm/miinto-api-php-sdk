<?php declare(strict_types=1);

namespace Shwrm\Miinto\ValueObject;

class MiintoCommunicationChannel
{
    /** @var string */
    private $token;

    /** @var string */
    private $channelId;

    public function __construct(string $token, string $channelId)
    {
        $this->token     = $token;
        $this->channelId = $channelId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public static function createFromResponse(array $response): self
    {
        return new self(
            $response['data']['token'],
            $response['data']['id']
        );
    }
}
