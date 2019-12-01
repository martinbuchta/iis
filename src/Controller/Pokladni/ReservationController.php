<?php


namespace App\Controller\Pokladni;


use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="pokladni_reservation_list")
     */
    public function list(ReservationRepository $reservationRepository)
    {
        $reservations = $reservationRepository->findAll();

        return $this->render('pokladni/reservation/list.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}