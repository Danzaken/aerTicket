<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flight[]    findAll()
 * @method Flight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flight::class);
    }

    public function getFilteredFlights(?string $departureAirport, ?string $arrivalAirport, ?string $departureDate)
    {
        $query = $this->createQueryBuilder('f')
            ->innerJoin('f.carrier', 'carrier')
            ->innerJoin('f.departureAirport', 'depAir')
            ->innerJoin('f.arrivalAirport', 'arrAir')
            ->orderBy('f.departureTime');

        if (!empty($departureAirport)) {
            $query->andWhere("depAir.name = :departureAirport")
                ->setParameter('departureAirport', $departureAirport);
        }
        if (!empty($arrivalAirport)) {
            $query->andWhere("arrAir.name = :arrivalAirport")
                ->setParameter('arrivalAirport', $arrivalAirport);
        }
        if (!empty($departureDate)) {
            $query->andWhere("DATE(f.departureTime) = :departureDate OR DATE(f.arrivalTime) = :departureDate")
                ->setParameter('departureDate', $departureDate);
        }
        return $query->getQuery()->getResult();
    }
}
