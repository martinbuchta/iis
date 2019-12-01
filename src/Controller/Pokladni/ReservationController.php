<?php


namespace App\Controller\Pokladni;


use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Form\ReservationEditType;
use App\Form\ReservationType;
use App\Repository\PerformanceRepository;
use App\Repository\ReservationRepository;
use App\Utils\ReservationCreator;
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
     * @Route("/admin/reservation/ticket-remove/{id}", name="pokladni_ticket_remove")
     * @IsGranted("ROLE_POKLADNI")
     */
    public function removeTicket(Ticket $ticket, EntityManagerInterface $entityManager)
    {
        if (count($ticket->getReservation()->getTickets()) < 2) {
            $this->addFlash('danger', 'Rezervace musí mít alespoň jedno místo.');
            return new RedirectResponse($this->generateUrl('pokladni_reservation_edit', [
                'id' => $ticket->getReservation()->getId(),
            ]));
        }

        $entityManager->remove($ticket);
        $entityManager->flush();

        return new RedirectResponse($this->generateUrl('pokladni_reservation_edit', [
            'id' => $ticket->getReservation()->getId(),
        ]));
    }

    /**
     * @Route("/admin/reservation/{id}-remove", name="pokladni_reservation_remove")
     * @IsGranted("ROLE_POKLADNI")
     */
    public function remove(Reservation $reservation, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reservation);
        $entityManager->flush();
        $this->addFlash('success', 'Rezervace byla zrušena.');
        return new RedirectResponse($this->generateUrl('pokladni_reservation_list'));
    }

    /**
     * @Route("/admin/reservation/{id}", name="pokladni_reservation_edit")
     * @IsGranted("ROLE_POKLADNI")
     */
    public function edit(Reservation $reservation, Request $request, EntityManagerInterface $entityManager, PerformanceRepository $performanceRepository, ReservationCreator $creator)
    {
        $form = $this->createForm(ReservationEditType::class, $reservation);
        $form->handleRequest($request);
        $performance = $reservation->getTickets()[0]->getPerformance();
        $orderedSeats = $performanceRepository->getAllOrderedSeats($performance);
        $orderedSeatsArray = [];
        foreach ($orderedSeats as $orderedSeat) {
            $orderedSeatsArray[] = $orderedSeat->toArray();
        }
        $seatsJson = $performance->getHall()->getSeatsJson();
        $orderedSeatsJson = json_encode($orderedSeatsArray);
        $hallJson = json_encode($performance->getHall()->toArray());

        $seatsForm = $this->createForm(ReservationType::class, $reservation, [
            'performance' => $performance,
            'anonymous' => false,
        ]);
        $seatsForm->handleRequest($request);

        if ($seatsForm->isSubmitted() && $seatsForm->isValid()) {
            $seats = $seatsForm->get("seats")->getData();

            if (false == $creator->areSeatsAvailable($seats, $performance)) {
                $this->addFlash('danger', 'Sedadla již nejsou k dispozici.');
                return new RedirectResponse($request->getUri());
            }

            $creator->addSeatsToReservations($reservation, $seats);
            $this->addFlash('success', 'Sedadla byla přidána.');
            return new RedirectResponse($request->getUri());
        }

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
            'performance' => $performance,
            'seatsJson' => $seatsJson,
            'orderedSeatsJson' => $orderedSeatsJson,
            'hallJson' => $hallJson,
            'seatsForm' => $seatsForm->createView(),
        ]);
    }
}