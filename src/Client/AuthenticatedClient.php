<?php declare(strict_types=1);

namespace Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Shwrm\Miinto\Generator\SeedGenerator;
use Shwrm\Miinto\ValueObject\AuthData;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class AuthenticatedClient extends BasicClient
{
    const AUTH_TYPE = 'MNT-HMAC-SHA256-1-0';

    /** @var AuthData */
    private $authData;

    /** @var MiintoCommunicationChannel */
    private $mcc;

    public function __construct(Client $client, AuthData $authData, string $baseUri)
    {
        $this->authData = $authData;

        parent::__construct($client, $baseUri);
    }

    public function doRequest(Request $request): string
    {
        $mcc = $this->getMiintoCommunicationChannel();

        $timestamp = time();
        $seed      = SeedGenerator::generate();

        $signature = $this->generateSignature($request, $mcc, $timestamp, $seed);

        $request = $request
            ->withHeader('Miinto-Api-Auth-ID', $mcc->getChannelId())
            ->withHeader('Miinto-Api-Auth-Signature', $signature)
            ->withHeader('Miinto-Api-Auth-Timestamp', (string)$timestamp)
            ->withHeader('Miinto-Api-Auth-Seed', (string)$seed)
            ->withHeader('Miinto-Api-Auth-Type', self::AUTH_TYPE)
            ->withHeader('Miinto-Api-Control-Flavour', 'Miinto-Generic')
            ->withHeader('Miinto-Api-Control-Version', '2.2')
        ;

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

    private function generateSignature(Request $request, MiintoCommunicationChannel $mcc, int $timestamp, int $seed): string
    {
        $resourceSignature = hash('sha256', sprintf(
            "%s\n%s\n%s\n%s",
            $request->getMethod(),
            $request->getUri()->getHost(),
            $request->getUri()->getPath(),
            $request->getUri()->getQuery()
        ));

        $headerSignature = hash('sha256', sprintf(
            "%s\n%s\n%s\n%s",
            $mcc->getChannelId(),
            $timestamp,
            $seed,
            self::AUTH_TYPE
        ));

        $payloadSignature = hash('sha256', (string)$request->getBody());

        $signature = hash_hmac(
            'sha256',
            sprintf(
                "%s\n%s\n%s",
                $resourceSignature,
                $headerSignature,
                $payloadSignature
            ),
            $mcc->getToken()
        );

        return $signature;
    }

    public function setPermaChannel(MiintoCommunicationChannel $channel): self
    {
        $this->mcc = $channel;

        return $this;
    }
}
