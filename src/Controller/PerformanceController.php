<?php

namespace App\Controller;

use App\Entity\Performance;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\PerformanceRepository;
use App\Security\PasswordChanger;
use App\Utils\ReservationCreator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PerformanceController extends AbstractController
{
    /**
     * @Route("/performance/{id}", name="performance_show")
     */
    public function show(Performance $performance, PerformanceRepository $performanceRepository, Request $request, ReservationCreator $creator, PasswordChanger $passwordChanger, EntityManagerInterface $entityManager)
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
            $hall = $performance->getHall();

            if (count($seats) < 1) {
                $this->addFlash('danger', 'Vyberte alespoň jedno sedadlo.');
                return new RedirectResponse($request->getUri());
            }

            if (count($seats) > 6) {
                if ($this->getUser() && ($this->getUser()->getRole() == "ROLE_ADMINISTRATOR" || in_array($hall, $this->getUser()->getHalls()))) {

                } else {
                    $this->addFlash('danger', 'Můžete si objednat nejvýše 6 sedadel.');
                    return new RedirectResponse($request->getUri());
                }
            }

            if (false == $creator->areSeatsAvailable($seats, $performance)) {
                $this->addFlash('danger', 'Vybraná sedadla již nejsou k dispozici. Vyberte si prosím jiná.');
                return new RedirectResponse($request->getUri());
            }

            $creator->createReservation($reservation, $seats, $performance, false);
            $plainPasswd = $seatsForm->get('plainPassword')->getData();

            if (strlen($plainPasswd) > 0) {
                // create new user
                $user = new User();
                $user->setName($reservation->getName());
                $user->setSurname($reservation->getSurname());
                $user->setEmail($reservation->getEmail());
                $user->setRole("ROLE_DIVAK");
                try {
                    $entityManager->persist($user);
                    $passwordChanger->changePassword($user, $plainPasswd, false);
                    $entityManager->flush();
                } catch (\Exception $exception) {
                    $seatsForm->addError(new FormError("Uživatel s tímto emailem již existuje. Přihlaste se, nebo nevyplňujte heslo."));
                    return $this->render('performance/show.html.twig', [
                        'performance' => $performance,
                        'seatsForm' => $seatsForm->createView(),
                        'seatsJson' => $seatsJson,
                        'orderedSeatsJson' => $orderedSeatsJson,
                        'hallJson' => $hallJson,
                    ]);
                }
                $this->addFlash('success', 'Byl vám vytvořen nový uživatelský účet. Můžete se přihlásit.');
            }

            $entityManager->flush();

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
