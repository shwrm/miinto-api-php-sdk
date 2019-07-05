<?php declare(strict_types=1);

namespace Shwrm\Miinto\Repository;

use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\Filter\Order\ListFilter;

class OrderRepository
{
    /** @var AuthenticatedClient */
    private $client;

    public function __construct(AuthenticatedClient $orderClient)
    {
        $this->client = $orderClient;
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
}
