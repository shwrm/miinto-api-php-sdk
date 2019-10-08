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
use Shwrm\Miinto\Client\PermaChannelClient;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class PermaChannelClientTest extends TestCase
{
    public function testDoRequest(): void
    {
        $container = [];
        $history   = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200, []),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        /** @var RequestSigner|MockObject $requestSigner */
        $requestSigner = $this->createMock(RequestSigner::class);

        $request = new Request('GET', 'test-url');
        $mcc     = new MiintoCommunicationChannel('token', 'channel-id');

        $requestSigner
            ->expects(self::once())
            ->method('sign')
            ->with($request, $mcc)
            ->willReturn(
                $request->withHeader('test-header', 'test-header-value')
            )
        ;

        $client     = new Client(['handler' => $handler]);
        $authClient = new PermaChannelClient($client, $mcc, 'test-url', $requestSigner);

        $authClient->doRequest($request);

        $this->assertCount(1, $container);

        /** @var Request $mainRequest */
        $mainRequest = $container[0]['request'];

        $this->assertArrayHasKey('test-header', $mainRequest->getHeaders());
    }
}
