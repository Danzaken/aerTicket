<?php

namespace App\Controller;

use App\Entity\Airport;
use App\Entity\Carrier;
use App\Repository\FlightRepository;
use App\Services\FlightService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Class ApiController
 *
 * @package App\Controller
 * @author Artur Kulych <kulych9@gmail.com>
 */
class ApiController extends AbstractController
{
    /**
     * @var FlightService
     */
    private $flightService;


    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * @Route("/api/search")
     *
     * @param Request $request
     * @param FlightRepository $flightRepository
     * @return Response
     */
    public function search(Request $request, FlightRepository $flightRepository): Response
    {
        $departureAirport = $request->get('departureAirport');
        $arrivalAirport = $request->get('arrivalAirport');
        $departureDate = $request->get('departureDate');
        if (empty($departureAirport)
            || empty($arrivalAirport)
            || empty($departureDate)
        ) {
            throw new NotFoundHttpException('departureAirport, arrivalAirport, departureDate must be set');
        } elseif (!$this->flightService->checkDateFormat($departureDate)) {
            throw new NotFoundHttpException('Wrong date format');
        } elseif (!$this->flightService->checkAirportName($departureAirport)) {
            throw new NotFoundHttpException('Wrong name in departureAirport');
        } elseif (!$this->flightService->checkAirportName($arrivalAirport)) {
            throw new NotFoundHttpException('Wrong name in arrivalAirport');
        }

        $flights = $flightRepository->getFilteredFlights($departureAirport, $arrivalAirport, $departureDate);
        if (empty($flights)) {
            throw new NotFoundHttpException('Empty result with this parameters');
        }
        return $this->json([
            'searchQuery' => $request->query->all(),
            'searchResults' => $this->flightService->formatFlightsArray($flights)
        ]);
    }

    /**
     * @Route("/api/add-default-flights")
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addDefaultFlights(Request $request): Response
    {
        $count = $request->get('count', 1);
        for ($index = 0; $index < $count; $index++) {
            $this->flightService->addDefaultFlight();
        }

        return $this->json(['status' => 'success']);
    }

    /**
     * @Route("/api/add-default-carrier")
     *
     * @return Response
     * @throws \Exception
     */
    public function addOneDefaultCarrier(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $carrier = new Carrier();
        $carrier->setCode(random_int(1, 100));
        $carrier->setName('Carrier' . $carrier->getCode());

        $entityManager->persist($carrier);
        $entityManager->flush();

        return $this->json(['status' => 'success']);
    }

    /**
     * @Route("/api/add-default-airport")
     *
     * @return Response
     * @throws \Exception
     */
    public function addOneDefaultAirport(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $airport = new Airport();
        $airport->setName('Airport' . random_int(1, 100));

        $entityManager->persist($airport);
        $entityManager->flush();

        return $this->json(['status' => 'success']);
    }
}