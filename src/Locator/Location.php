<?php

declare(strict_types=1);

namespace App\Locator;

final readonly class Location
{
    public function __construct(
        private string $country,
        private ?string $region = null,
        private ?string $city = null,
    ) {}

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function __toString(): string
    {
        $location = $this->country;

        if (! is_null($this->region)) {
            $location .= ' - ' . $this->region;
        }


        if (! is_null($this->city)) {
            $location .= ' - ' . $this->city;
        }

        return $location;
    }
}
