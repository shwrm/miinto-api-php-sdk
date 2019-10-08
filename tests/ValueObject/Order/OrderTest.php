<?php declare(strict_types=1);

namespace Test\Shwrm\Miinto\ValueObject\Order;

use PHPUnit\Framework\TestCase;
use Shwrm\Miinto\ValueObject\Order\Order;
use Shwrm\Miinto\ValueObject\Order\Position;

class OrderTest extends TestCase
{
    public function testCreateFromResponse(): void
    {
        $response = [
            'data' => [
                'parentOrderId' => 'test-id',
                'items'         => [
                    [
                        'miintoId' => 'miinto-id',
                        'status'   => Position::STATUS_DECLINED,
                    ],
                ],
            ],
        ];

        $order = Order::createFromResponse($response);

        $this->assertEquals('test-id', $order->getOrderId());
        $this->assertCount(1, $order->getPositions());
    }
}
