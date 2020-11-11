<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $carrierId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $flightNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departureTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $arrivalTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $arrivalAirportId;
    /**
     * @ORM\Column(type="integer")
     */
    private $departureAirportId;

    /**
     * @ORM\ManyToOne(targetEntity=Carrier::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier;

    /**
     * @ORM\ManyToOne(targetEntity=Airport::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $departureAirport;

    /**
     * @ORM\ManyToOne(targetEntity=Airport::class)
     */
    private $arrivalAirport;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierId(): ?string
    {
        return $this->carrierId;
    }

    public function setCarrierId(int $carrierId): self
    {
        $this->carrierId = $carrierId;

        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(string $flightNumber): self
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    public function getDepartureTime(): ?string
    {
        return $this->departureTime;
    }

    public function setDepartureTime(string $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getArrivalTime(): ?string
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(string $arrivalTime): self
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getArrivalAirportId(): ?int
    {
        return $this->arrivalAirportId;
    }

    public function setArrivalAirportId(int $arrivalAirportId): self
    {
        $this->arrivalAirportId = $arrivalAirportId;

        return $this;
    }

    public function getDepartureAirportId(): ?int
    {
        return $this->departureAirportId;
    }

    public function setDepartureAirportId(int $departureAirportId): self
    {
        $this->departureAirportId = $departureAirportId;

        return $this;
    }

    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function setCarrier(?Carrier $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getDepartureAirport(): ?Airport
    {
        return $this->departureAirport;
    }

    public function setDepartureAirport(?Airport $departureAirport): self
    {
        $this->departureAirport = $departureAirport;

        return $this;
    }

    public function getArrivalAirport(): ?Airport
    {
        return $this->arrivalAirport;
    }

    public function setArrivalAirport(?Airport $arrivalAirport): self
    {
        $this->arrivalAirport = $arrivalAirport;

        return $this;
    }
}
