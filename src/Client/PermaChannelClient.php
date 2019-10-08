<?php declare(strict_types=1);

namespace Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class PermaChannelClient extends SignedClient
{
    /** @var RequestSigner */
    private $requestSigner;

    /** @var MiintoCommunicationChannel */
    private $mcc;

    public function __construct(Client $client, MiintoCommunicationChannel $mcc, string $baseUri, RequestSigner $requestSigner = null)
    {
        $this->mcc           = $mcc;
        $this->requestSigner = $requestSigner ?? new RequestSigner();

        parent::__construct($client, $baseUri);
    }

    protected function getMiintoCommunicationChannel(): MiintoCommunicationChannel
    {
        return $this->mcc;
    }

    protected function getRequestSigner(): RequestSigner
    {
        return $this->requestSigner;
    }
}
