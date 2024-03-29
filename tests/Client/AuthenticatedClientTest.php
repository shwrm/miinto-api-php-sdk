<?php declare(strict_types=1);

namespace Test\Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\AuthData;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class AuthenticatedClientTest extends TestCase
{
    public function testDoRequest(): void
    {
        $container = [];
        $history   = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, [], $this->getFixtures('AuthApiResponse')),
            new Response(200, []),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        /** @var RequestSigner|MockObject $requestSigner */
        $requestSigner = $this->createMock(RequestSigner::class);

        $request = new Request('GET', 'test-url');

        $mccData = \GuzzleHttp\json_decode($this->getFixtures('AuthApiResponse'), true);

        $mcc = MiintoCommunicationChannel::createFromResponse($mccData);

        $requestSigner
            ->expects(self::once())
            ->method('sign')
            ->with($request, $mcc)
            ->willReturn(
                $request->withHeader('test-header', 'test-header-value')
            )
        ;

        $client     = new Client(['handler' => $handler]);
        $authData   = new AuthData('api-auth-url', 'showroomtest', 'qwerty');
        $authClient = new AuthenticatedClient($client, $authData, 'test-url', $requestSigner);

        $authClient->doRequest($request);

        $this->assertCount(2, $container);

        /** @var Request $sessionRequest */
        $sessionRequest = $container[0]['request'];;

        $this->assertEquals('api-auth-url/channels', $sessionRequest->getUri()->getPath());
        $this->assertEquals('POST', $sessionRequest->getMethod());
        $this->assertEquals('{"identifier":"showroomtest","secret":"qwerty"}', $sessionRequest->getBody()->__toString());

        /** @var Request $mainRequest */
        $mainRequest = $container[1]['request'];

        $this->assertArrayHasKey('test-header', $mainRequest->getHeaders());
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
