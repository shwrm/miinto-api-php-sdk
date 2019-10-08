<?php declare(strict_types=1);

namespace Test\Shwrm\Miinto\Utils;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class RequestSignerTest extends TestCase
{
    public function testSign(): void
    {
        $request = new Request('GET', 'test-url');
        $mcc     = new MiintoCommunicationChannel('token', 'channel-id');
        $signer  = new RequestSigner();

        $signedRequest = $signer->sign($request, $mcc);

        $this->assertArrayHasKey('Miinto-Api-Auth-ID', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Signature', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Timestamp', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Seed', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Auth-Type', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Control-Flavour', $signedRequest->getHeaders());
        $this->assertArrayHasKey('Miinto-Api-Control-Version', $signedRequest->getHeaders());

        $this->assertEquals('2.6', $signedRequest->getHeader('Miinto-Api-Control-Version')[0]);
    }
}
