<?php declare(strict_types=1);

namespace Shwrm\Miinto\Payload;

use JMS\Serializer\Annotation as JMS;

class Position
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("miintoId")
     */
    private $miintoId;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\SerializedName("quantity")
     */
    private $quantity;

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\SerializedName("price")
     */
    private $price;

    /**
     * @var array
     * @JMS\Type("array")
     * @JMS\SerializedName("strategyData")
     */
    private $strategyData;

    public function __construct(
        string $miintoId,
        int $quantity,
        int $price,
        array $strategyData
    ) {
        $this->miintoId     = $miintoId;
        $this->quantity     = $quantity;
        $this->price        = $price;
        $this->strategyData = $strategyData;
    }

    public function getMiintoId(): string
    {
        return $this->miintoId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getStrategyData(): array
    {
        return $this->strategyData;
    }
}
