<?php declare(strict_types=1);

namespace Shwrm\Miinto\Filter\Order;

class ListFilter
{
    const STATUS_PENDING  = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    const SORT_CREATED_AT_ASC = '+createdAt';
    const SORT_CREATED_AT_DESC = '-createdAt';

    const STATUSES = [
        self::STATUS_ACCEPTED,
        self::STATUS_DECLINED,
        self::STATUS_PENDING,
    ];

    /** @var string */
    private $sort = self::SORT_CREATED_AT_DESC;

    /** @var int */
    private $limit = 10;

    /** @var array */
    private $statuses = [];

    /** @var string */
    private $locationId;

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): ListFilter
    {
        $this->sort = $sort;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): ListFilter
    {
        $this->limit = $limit;

        return $this;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function setStatuses(array $statuses): ListFilter
    {
        $this->statuses = $statuses;

        return $this;
    }

    public function isStatusesEmpty(): bool
    {
        return empty($this->statuses);
    }

    public function getLocationId(): string
    {
        return $this->locationId;
    }

    public function setLocationId(string $locationId): ListFilter
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function isLocationIdEmpty(): bool
    {
        return empty($this->locationId);
    }
}
