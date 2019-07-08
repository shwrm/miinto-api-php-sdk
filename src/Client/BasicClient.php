<?php declare(strict_types=1);

namespace Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Shwrm\Miinto\Exception\ClientException;

class BasicClient
{
    /** @var Client */
    private $httpClient;

    /** @var string */
    private $baseUri;

    public function __construct(Client $client, string $baseUri)
    {
        $this->httpClient = $client;
        $this->baseUri    = $baseUri;
    }

    public function getDefaultHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        return $headers;
    }

    public function post(string $uri, array $headers = [], $body = null): string
    {

        $request = new Request(
            'POST',
            sprintf('%s%s', $this->baseUri, $uri),
            array_merge_recursive($headers, $this->getDefaultHeaders()),
            $body
        );

        return $this->doRequest($request);
    }

    public function get(string $uri, array $headers = []): string
    {
        $request = new Request(
            'GET',
            sprintf('%s%s', $this->baseUri, $uri),
            array_merge_recursive($headers, $this->getDefaultHeaders())
        );

        return $this->doRequest($request);
    }

    public function doRequest(Request $request): string
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (GuzzleException $e) {
            throw new ClientException('Request failed', $request, $e);
        }

        return (string)$response->getBody();
    }
}
