<?php declare(strict_types=1);

namespace Shwrm\Miinto\Payload;

use JMS\Serializer\Annotation as JMS;

class Order
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $salesChannelId;

    /**
     * @var BillingInformation
     * @JMS\Type("Shwrm\Miinto\Payload\BillingInformation")
     */
    private $billingInformation;

    /**
     * @var ShippingInformation
     * @JMS\Type("Shwrm\Miinto\Payload\ShippingInformation")
     */
    private $shippingInformation;

    /**
     * @var Position[]
     * @JMS\Type("array<Shwrm\Miinto\Payload\Position>")
     */
    private $positions;

    public function __construct(
        string $salesChannelId,
        BillingInformation $billingInformation,
        ShippingInformation $shippingInformation,
        array $positions
    ) {
        $this->salesChannelId      = $salesChannelId;
        $this->billingInformation  = $billingInformation;
        $this->shippingInformation = $shippingInformation;
        $this->positions           = $positions;
    }

    public function getSalesChannelId(): string
    {
        return $this->salesChannelId;
    }

    public function getBillingInformation(): BillingInformation
    {
        return $this->billingInformation;
    }

    public function getShippingInformation(): ShippingInformation
    {
        return $this->shippingInformation;
    }

    public function getPositions(): array
    {
        return $this->positions;
    }
}
