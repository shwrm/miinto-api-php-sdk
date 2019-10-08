<?php declare(strict_types=1);

namespace Shwrm\Miinto\ValueObject\Order;

class Order
{
    /** @var string */
    private $orderId;

    /** @var Position[] */
    private $positions;

    public function __construct(string $orderId, array $positions)
    {
        $this->orderId   = $orderId;
        $this->positions = $positions;
    }

    public static function createFromResponse(array $response)
    {
        foreach ($response['data']['items'] as $position) {
            $positions[] = Position::createFromArray($position);
        }

        return new self($response['data']['parentOrderId'], $positions ?? []);
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return Position[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }
}
