<?php declare(strict_types=1);

namespace Shwrm\Miinto\ValueObject;

class MiintoPosition
{
    const STATUS_PENDING  = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /** @var string */
    private $miintoId;

    /** @var string */
    private $status;

    /** @var string|null */
    private $trackAndTrace;

    /** @var string|null */
    private $shippingProvider;

    public function __construct(string $miintoId, string $status, ?string $trackAndTrace, ?string $shippingProvider)
    {
        $this->miintoId         = $miintoId;
        $this->status           = $status;
        $this->trackAndTrace    = $trackAndTrace;
        $this->shippingProvider = $shippingProvider;
    }

    public static function createFromArray(array $data)
    {
        return new self(
            $data['miintoId'],
            $data['status'],
            $data['trackAndTrace'] ?? null,
            $data['shippingProviderId'] ?? null
        );
    }

    public function getMiintoId(): string
    {
        return $this->miintoId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTrackAndTrace(): ?string
    {
        return $this->trackAndTrace;
    }

    public function getShippingProvider(): ?string
    {
        return $this->shippingProvider;
    }
}
