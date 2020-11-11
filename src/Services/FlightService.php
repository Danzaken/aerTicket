<?php

namespace App\Services;

use App\Entity\Airport;
use App\Entity\Carrier;
use App\Entity\Flight;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * Class PlaneService
 *
 * @package App\Services
 * @author Artur Kulych <kulych9@gmail.com>
 * @copyright (c), Thread
 */
class FlightService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var Carrier[]
     */
    private $carriers = null;
    /**
     * @var Airport[]
     */
    private $airports = null;
    /**
     * @var Airport[]
     */
    private $indexedAirportNames = null;

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function addDefaultFlight()
    {
        $flight = new Flight();

        $flight->setFlightNumber(md5(random_bytes(64)));

        $flight->setCarrier($this->getRandomCarrier());
        $flight->setArrivalAirport($this->getRandomAirport());
        do {
            $flight->setDepartureAirport($this->getRandomAirport());
        } while ($flight->getDepartureAirport()->getId() === $flight->getArrivalAirport()->getId());

        $flight->setDepartureTime(
            (new DateTime())
                ->setTimestamp(random_int(time(), (new DateTime())->modify('+5 year')->getTimestamp()))
                ->format('Y-m-d H:i')
        );
        $flight->setDuration(random_int(40, 360));
        $flight->setArrivalTime(
            (new DateTime($flight->getDepartureTime()))
                ->modify('+' . $flight->getDuration() . 'minutes')
                ->format('Y-m-d H:i')
        );

        $this->entityManager->persist($flight);
        $this->entityManager->flush();
    }

    /**
     * @param Flight[]|array $flights
     */
    public function formatFlightsArray(array $flights)
    {
        $formattedFlights = [];
        foreach ($flights as $flight) {
            $formattedFlights[] = [
                'transporter' => [
                    'name' => $flight->getCarrier()->getName(),
                    'code' => $flight->getCarrier()->getCode()
                ],
                'flightNumber' => $flight->getFlightNumber(),
                'departureAirport' => $flight->getDepartureAirport()->getName(),
                'arrivalAirport' => $flight->getArrivalAirport()->getName(),
                'departureDateTime' => $flight->getDepartureTime(),
                'arrivalDateTime' => $flight->getArrivalTime(),
                'duration' => $flight->getDuration()
            ];
        }
        return $formattedFlights;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkAirportName(string $name)
    {
        return array_key_exists($name, $this->getAirportsNames());
    }

    /**
     * @param string $date
     * @return bool
     * @throws \Exception
     */
    public function checkDateFormat(string $date)
    {
        try {
            $result = ((new DateTime($date))->format('Y-m-d') === $date);
        } catch (Throwable $exception) {
            $result = false;
        }
        return $result;
    }

    /**
     * @return Carrier|mixed|null
     * @throws \Exception
     */
    private function getRandomCarrier()
    {
        $carriers = $this->getCarriers();
        if (!empty($carriers)) {
            return $carriers[random_int(0, count($carriers) - 1)];
        } else {
            return null;
        }
    }

    /**
     * @return Carrier|mixed|null
     * @throws \Exception
     */
    private function getRandomAirport()
    {
        $airports = $this->getAirports();
        if (!empty($airports)) {
            return $airports[random_int(0, count($airports) - 1)];
        } else {
            return null;
        }
    }

    /**
     * @return Carrier[]|array
     */
    private function getCarriers()
    {
        if ($this->carriers === null) {
            $carrierRepository = $this->doctrine->getRepository(Carrier::class);
            $this->carriers = $carrierRepository->findAll();
        }
        return $this->carriers;
    }

    /**
     * @return Carrier[]|array
     */
    private function getAirports()
    {
        if ($this->airports === null) {
            $airportsRepository = $this->doctrine->getRepository(Airport::class);
            $this->airports = $airportsRepository->findAll();
        }
        return $this->airports;
    }
    private function getAirportsNames()
    {

        if ($this->indexedAirportNames === null) {
            foreach ($this->getAirports() as $airport) {
                $this->indexedAirportNames[$airport->getName()] = true;
            }
        }
        return $this->indexedAirportNames;
    }
}