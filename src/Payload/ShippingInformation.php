<?php declare(strict_types=1);

namespace Shwrm\Miinto\Payload;

use JMS\Serializer\Annotation as JMS;

class ShippingInformation
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $provider;

    /**
     * @var DeliveryData
     * @JMS\Type("Shwrm\Miinto\Payload\DeliveryData")
     */
    private $deliveryData;

    public function __construct(string $type, string $provider, DeliveryData $deliveryData)
    {
        $this->type         = $type;
        $this->provider     = $provider;
        $this->deliveryData = $deliveryData;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getDeliveryData(): DeliveryData
    {
        return $this->deliveryData;
    }
}
