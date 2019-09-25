<?php declare(strict_types=1);

namespace Shwrm\Miinto\ValueObject;

class MiintoOrder
{
    /** @var string */
    private $orderId;

    /** @var MiintoPosition[] */
    private $positions;

    public function __construct(string $orderId, array $positions)
    {
        $this->orderId   = $orderId;
        $this->positions = $positions;
    }

    public static function createFromArray(array $data)
    {
        foreach ($data['items'] as $position) {
            $positions[] = MiintoPosition::createFromArray($position);
        }

        return new self($data['parentOrderId'], $positions ?? []);
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return MiintoPosition[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }
}
