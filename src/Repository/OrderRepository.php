<?php declare(strict_types=1);

namespace Shwrm\Miinto\Repository;

use GuzzleHttp\Exception\BadResponseException;
use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\Client\SignedClient;
use Shwrm\Miinto\Exception\ClientException;
use Shwrm\Miinto\ValueObject\Order\Order;

class OrderRepository
{
    /** @var string */
    const COUNTRY_PL = 'pl';

    /** @var AuthenticatedClient */
    private $client;

    public function __construct(SignedClient $orderClient)
    {
        $this->client = $orderClient;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ClientException
     */
    public function getOrder(int $orderId, string $country): Order
    {
        try {
            $response = \GuzzleHttp\json_decode(
                $this->client->get(sprintf('countries/%s/orders/%s', $country, $orderId)),
                true
            );
        } catch (BadResponseException $exception) {
            throw new ClientException($exception->getMessage(), $exception->getRequest());
        }

        return Order::createFromResponse($response);
    }
}
