<?php

namespace App\Controller;

use App\Entity\Performance;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\PerformanceRepository;
use App\Utils\ReservationCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PerformanceController extends AbstractController
{
    /**
     * @Route("/performance/{id}", name="performance_show")
     */
    public function show(Performance $performance, PerformanceRepository $performanceRepository, Request $request, ReservationCreator $creator)
    {
        $reservation = new Reservation();

        if ($this->getUser()) {
            $reservation->setUser($this->getUser());
        }

        $seatsForm = $this->createForm(ReservationType::class, $reservation, [
            'performance' => $performance,
            'anonymous' => ($this->getUser()) ? false : true,
        ]);
        $orderedSeats = $performanceRepository->getAllOrderedSeats($performance);
        $orderedSeatsArray = [];
        foreach ($orderedSeats as $orderedSeat) {
            $orderedSeatsArray[] = $orderedSeat->toArray();
        }

        $seatsJson = $performance->getHall()->getSeatsJson();
        $orderedSeatsJson = json_encode($orderedSeatsArray);
        $hallJson = json_encode($performance->getHall()->toArray());

        $seatsForm->handleRequest($request);

        if ($seatsForm->isSubmitted() && $seatsForm->isValid()) {
            $seats = $seatsForm->get('seats')->getData();
            if (false == $creator->areSeatsAvailable($seats, $performance)) {
                $this->addFlash('danger', 'Vybraná sedadla již nejsou k dispozici. Vyberte si prosím jiná.');
                return new RedirectResponse($request->getUri());
            }

            $creator->createReservation($reservation, $seats, $performance);
            $this->addFlash('success', 'Rezervace byla úspěšně vytvořena.');
            return new RedirectResponse($this->generateUrl('homepage'));
        }

        return $this->render('performance/show.html.twig', [
            'performance' => $performance,
            'seatsForm' => $seatsForm->createView(),
            'seatsJson' => $seatsJson,
            'orderedSeatsJson' => $orderedSeatsJson,
            'hallJson' => $hallJson,
        ]);
    }
}
