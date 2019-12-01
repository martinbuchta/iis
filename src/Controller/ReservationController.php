<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/my-reservation", name="my_reservation")
     * @IsGranted("ROLE_DIVAK")
     */
    public function list()
    {
        $user = $this->getUser();
        $reservations = $user->getReservations();

        return $this->render('my_reservations.html.twig',[
            'reservations' => array_reverse($reservations),
        ]);
    }
}
