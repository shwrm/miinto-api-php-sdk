<?php declare(strict_types=1);

namespace Shwrm\Miinto\Repository;

use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\ValueObject\MiintoOrder;

class OrderRepository
{
    /** @var AuthenticatedClient */
    private $client;

    public function __construct(AuthenticatedClient $orderClient)
    {
        $this->client = $orderClient;
    }

    public function getOrder(int $orderId, string $country): MiintoOrder
    {
        $response = \GuzzleHttp\json_decode(
            $this->client->get(sprintf('/countries/%s/orders/%s', $country, $orderId)),
            true
        );

        return MiintoOrder::createFromArray(json_decode($response['data'], true));
    }

}
