<?php declare(strict_types=1);

namespace Shwrm\Miinto\Repository;

use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\Client\SignedClient;
use Shwrm\Miinto\ValueObject\Order\Order;

class OrderRepository
{
    /** @var AuthenticatedClient */
    private $client;

    public function __construct(SignedClient $orderClient)
    {
        $this->client = $orderClient;
    }

    public function getOrder(int $orderId, string $country): Order
    {
        $response = \GuzzleHttp\json_decode(
            $this->client->get(sprintf('/countries/%s/orders/%s', $country, $orderId)),
            true
        );

        return Order::createFromResponse($response);
    }
}
