<?php declare(strict_types=1);

namespace Test\Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\ValueObject\AuthData;

class AuthenticatedClientTest extends TestCase
{
    public function testDoRequest(): void
    {
        $container = [];
        $history   = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, [], $this->getFixtures('AuthApiResponse')),
            new Response(200, [], $this->getFixtures('GetOrderPositionsResponse')),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client     = new Client(['handler' => $handler]);
        $authData   = new AuthData('api-auth-url', 'showroomtest', 'qwerty');
        $authClient = new AuthenticatedClient($client, $authData, 'test-url');

        $authClient->doRequest(new Request('GET', ''));

        $this->assertCount(2, $container);

        /** @var Request $sessionRequest */
        $sessionRequest = $container[0]['request'];

        $this->assertEquals('api-auth-url/channels', $sessionRequest->getUri()->getPath());
        $this->assertEquals('POST', $sessionRequest->getMethod());

        /** @var Request $signedRequest */
        $signedRequest = $container[1]['request'];

        $this->assertArrayHasKey('Miinto-Api-Auth-ID', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Signature', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Timestamp', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Seed', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Type', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Control-Flavour', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Control-Version', $signedRequest->getHeaders());

        $this->assertEquals('2.2', $signedRequest->getHeader('Miinto-Api-Control-Version')[0]);
    }

    public function getFixtures(string $name): string
    {
        $path = sprintf('%s/../../tests/Fixtures/%s.json', __DIR__, $name);

        if (false === file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not exists.', $path));
        }

        return file_get_contents($path);
    }
}
