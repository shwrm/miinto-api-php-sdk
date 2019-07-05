<?php declare(strict_types=1);

namespace Test\Shwrm\Miinto\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shwrm\Miinto\Client\AuthenticatedClient;
use Shwrm\Miinto\Filter\Order\ListFilter;
use Shwrm\Miinto\Repository\OrderRepository;

class OrderRepositoryTest extends TestCase
{
    public function testGetOrder(): void
    {
        $filter = new ListFilter();
        $options = ['sort' => $filter->getSort(), 'limit' => $filter->getLimit(),];

        $expectedUrl = sprintf('/orders/%s/positions?%s', 1, http_build_query($options));


        /** @var AuthenticatedClient|MockObject $client */
        $client = $this->createMock(AuthenticatedClient::class);
        $client
            ->expects(self::once())
            ->method('get')
            ->with($expectedUrl)
            ->willReturn(\GuzzleHttp\json_encode(['data' => 'test-data']))
        ;

        $repository = new OrderRepository($client);

        $this->assertEquals('test-data', $repository->getOrder(1, $filter));
    }
}
