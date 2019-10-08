<?php declare(strict_types=1);

namespace Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\AuthData;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class AuthenticatedClient extends BasicClient
{
    /** @var AuthData */
    private $authData;

    /** @var MiintoCommunicationChannel */
    private $mcc;

    /** @var RequestSigner */
    private $requestSigner;

    public function __construct(Client $client, AuthData $authData, string $baseUri, RequestSigner $requestSigner = null)
    {
        $this->authData      = $authData;
        $this->requestSigner = $requestSigner ?? new RequestSigner();

        parent::__construct($client, $baseUri);
    }

    public function doRequest(Request $request): string
    {
        $mcc     = $this->getMiintoCommunicationChannel();
        $request = $this->requestSigner->sign($request, $mcc);

        return parent::doRequest($request);
    }

    private function getMiintoCommunicationChannel(): MiintoCommunicationChannel
    {
        if (null !== $this->mcc) {
            return $this->mcc;
        }

        $body = \GuzzleHttp\json_encode(
            [
                'identifier' => $this->authData->getIdentifier(),
                'secret'     => $this->authData->getSecret(),
            ]
        );

        $request = new Request(
            'POST',
            sprintf('%s/%s', $this->authData->getApiUrl(), 'channels'),
            $this->getDefaultHeaders(),
            $body
        );

        $response = \GuzzleHttp\json_decode(
            parent::doRequest($request),
            true
        );

        return MiintoCommunicationChannel::createFromResponse($response);
    }
}
