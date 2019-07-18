<?php declare(strict_types=1);

namespace Shwrm\Miinto\Repository;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\Filter\Order\ListFilter;
use Shwrm\Miinto\Payload\Order;

class OrderRepository
{
    /** @var AuthenticatedClient */
    private $client;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(AuthenticatedClient $orderClient)
    {
        $this->client     = $orderClient;
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function getOrder(int $orderId, ListFilter $filter)
    {
        $options = [
            'sort'  => $filter->getSort(),
            'limit' => $filter->getLimit(),
        ];

        if (false === $filter->isStatusesEmpty()) {
            $options = array_merge($options, ['positionStatuses' => $filter->getStatuses()]);
        }

        if (false === $filter->isLocationIdEmpty()) {
            $options = array_merge($options, ['locationId' => $filter->getLocationId()]);
        }

        $url = sprintf('/orders/%s/positions?%s', $orderId, http_build_query($options));

        $response = \GuzzleHttp\json_decode(
            $this->client->get($url),
            true
        );

        return $response['data'];
    }

    public function createOrder(Order $order)
    {
        $body = $this->serializer->serialize($order, 'json');

        $response = \GuzzleHttp\json_decode(
            $this->client->post('/orders', [], $body),
            true
        );

        return $response['data'];
    }
}
