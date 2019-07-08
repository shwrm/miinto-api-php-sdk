<?php declare(strict_types=1);

namespace Test\Shwrm\Miinto\ValueObject;

use PHPUnit\Framework\TestCase;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class MiintoCommunicationChannelTest extends TestCase
{
    public function testCreateFromResponse(): void
    {
        $response = [
            'data' => [
                'token' => 'test-token',
                'id'    => 'id',
            ],
        ];

        $mcc = MiintoCommunicationChannel::createFromResponse($response);

        $this->assertEquals('test-token', $mcc->getToken());
        $this->assertEquals('id', $mcc->getChannelId());
    }
}
