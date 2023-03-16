<?php

declare(strict_types=1);

namespace App\Core\Donation;

class Address
{
    private string $resume;
    private string $street;
    private string $number;
    private string $neighborhood;
    private string $complement;
    private string $city;
    private string $state;
    private string $country;
    private string $zipcode;
    private string $coordinates;

    public function __construct(
        ?string $resume,
        string $street,
        string $city,
        string $complement,
        string $number,
        string $state,
        string $neighborhood,
        ?string $zipcode,
        ?string $coordinates,
        string $country
    )
    {
        $this->resume = $resume;
        $this->street = $street;
        $this->city = $city;
        $this->complement = $complement;
        $this->number = $number;
        $this->state = $state;
        $this->neighborhood = $neighborhood;
        $this->zipcode = $zipcode;
        $this->coordinates = $coordinates;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getResume(): string
    {
        return $this->resume;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    /**
     * @return string
     */
    public function getComplement(): string
    {
        return $this->complement;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return strtolower($this->country);
    }

    /**
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode ?: '';
    }

    /**
     * @return string
     */
    public function getCoordinates(): string
    {
        return $this->coordinates;
    }

}
