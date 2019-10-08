<?php declare(strict_types=1);

namespace Shwrm\Miinto\Utils;

use GuzzleHttp\Psr7\Request;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class RequestSigner
{
    const AUTH_TYPE = 'MNT-HMAC-SHA256-1-0';

    public function sign(Request $request, MiintoCommunicationChannel $mcc): Request
    {
        $timestamp = time();
        $seed      = SeedGenerator::generate();

        $signature = $this->generateSignature($request, $mcc, $timestamp, $seed);

        /** @var Request $request */
        $request = $request
            ->withHeader('Miinto-Api-Auth-ID', $mcc->getChannelId())
            ->withHeader('Miinto-Api-Auth-Signature', $signature)
            ->withHeader('Miinto-Api-Auth-Timestamp', (string)$timestamp)
            ->withHeader('Miinto-Api-Auth-Seed', (string)$seed)
            ->withHeader('Miinto-Api-Auth-Type', self::AUTH_TYPE)
            ->withHeader('Miinto-Api-Control-Flavour', 'Miinto-Generic')
            ->withHeader('Miinto-Api-Control-Version', '2.6')
        ;

        return $request;
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
}
