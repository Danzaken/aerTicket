<?php

namespace App\Controller;

use App\Repository\FlightRepository;
use App\Services\FlightService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WebController
 *
 * @package App\Controller
 * @author Artur Kulych <kulych9@gmail.com>
 */
class WebController extends AbstractController
{
    /**
     * @Route("/web/search")
     *
     * @param Request $request
     * @param FlightService $flightService
     * @param FlightRepository $flightRepository
     * @return Response
     */
    public function search(Request $request, FlightService $flightService, FlightRepository $flightRepository): Response
    {
        $departureAirport = $request->get('departure-airport');
        $arrivalAirport = $request->get('arrival-airport');
        $departureDate = $request->get('departure-date');
        if (empty($departureAirport)
            && empty($arrivalAirport)
            && empty($departureDate)
        ) {
            return $this->render('main.html.twig', [
                'searchQuery' => [],
                'flightsKeys' => [],
                'flights' => []
            ]);
        } elseif (empty($departureAirport)
            || empty($arrivalAirport)
            || empty($departureDate)
        ) {
            throw new NotFoundHttpException('departureAirport, arrivalAirport, departureDate must be set');
        } elseif (!$flightService->checkDateFormat($departureDate)) {
            throw new NotFoundHttpException('Wrong date format');
        } elseif (!$flightService->checkAirportName($departureAirport)) {
            throw new NotFoundHttpException('Wrong name in departureAirport');
        } elseif (!$flightService->checkAirportName($arrivalAirport)) {
            throw new NotFoundHttpException('Wrong name in arrivalAirport');
        }

        $flights = $flightRepository->getFilteredFlights($departureAirport, $arrivalAirport, $departureDate);
        if (empty($flights)) {
            throw new NotFoundHttpException('Empty result with this parameters');
        }
        $formattedFlights = $flightService->formatFlightsArray($flights);
        return $this->render('main.html.twig', [
            'searchQuery' => $request->query->all(),
            'flightsKeys' => array_keys($formattedFlights[0]),
            'flights' => $formattedFlights
        ]);
    }
}