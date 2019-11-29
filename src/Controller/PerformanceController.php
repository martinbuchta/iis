<?php

namespace App\Controller;

use App\Entity\Performance;
use App\Form\ReservationSeatsType;
use App\Repository\PerformanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PerformanceController extends AbstractController
{
    /**
     * @Route("/performance/{id}", name="performance_show")
     */
    public function show(Performance $performance, PerformanceRepository $performanceRepository)
    {
        $seatsForm = $this->createForm(ReservationSeatsType::class, null, ['performance' => $performance]);
        $orderedSeats = $performanceRepository->getAllOrderedSeats($performance);

        $seatsJson = $performance->getHall()->getSeatsJson();
        $orderedSeatsJson = json_encode($orderedSeats);
        $hallJson = json_encode($performance->getHall()->toArray());

        return $this->render('performance/show.html.twig', [
            'performance' => $performance,
            'seatsForm' => $seatsForm->createView(),
            'seatsJson' => $seatsJson,
            'orderedSeatsJson' => $orderedSeatsJson,
            'hallJson' => $hallJson,
        ]);
    }
}
