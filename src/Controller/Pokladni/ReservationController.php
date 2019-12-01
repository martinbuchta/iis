<?php


namespace App\Controller\Pokladni;


use App\Entity\Reservation;
use App\Form\ReservationEditType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="pokladni_reservation_list")
     * @IsGranted("ROLE_POKLADNI")
     */
    public function list(ReservationRepository $reservationRepository)
    {
        $reservations = $reservationRepository->findBy([], [
            'createdAt' => 'DESC',
        ]);

        return $this->render('pokladni/reservation/list.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/admin/reservation/{id}", name="pokladni_reservation_edit")
     * @IsGranted("ROLE_POKLADNI")
     */
    public function edit(Reservation $reservation, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ReservationEditType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($reservation->getUser() != null) {
                $reservation->setName(null);
                $reservation->setSurname(null);
                $reservation->setEmail(null);
            } else {
                if (
                    null == $reservation->getName() ||
                    null == $reservation->getSurname() ||
                    null == $reservation->getEmail()
                ) {
                    $this->addFlash('danger', 'Vyplňte prosím jméno, příjemní a email.');
                    return new RedirectResponse($request->getUri());
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Rezervace byla upravena.');
            return new RedirectResponse($request->getUri());
        }

        return $this->render('pokladni/reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
}