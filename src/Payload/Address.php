<?php declare(strict_types=1);

namespace Shwrm\Miinto\Payload;

use JMS\Serializer\Annotation as JMS;

abstract class Address
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName('name')
     */
    private $name;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName('email')
     */
    private $email;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName('phone')
     */
    private $phone;

    /**
     * @var array
     * @JMS\Type("array")
     * @JMS\SerializedName('address')
     */
    private $address;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $street,
        string $postcode,
        string $city,
        string $country
    ) {
        $this->name  = $name;
        $this->email = $email;
        $this->phone = $phone;

        $this->address = [
            'phone'    => $phone,
            'street'   => $street,
            'postcode' => $postcode,
            'city'     => $city,
            'country'  => $country,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getAddress(): array
    {
        return $this->address;
    }
}
