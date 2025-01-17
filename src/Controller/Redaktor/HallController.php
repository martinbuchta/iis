<?php

namespace App\Controller\Redaktor;

use App\Entity\Hall;
use App\Form\HallType;
use App\Repository\HallRepository;
use App\Utils\Hall\SeatsManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HallController extends AbstractController
{
    /**
     * @Route("/admin/hall", name="redaktor_hall_list")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function list(HallRepository $hallRepository)
    {
        $halls = $hallRepository->findAll();

        return $this->render('redaktor/hall/list.html.twig', [
            'halls' => $halls,
        ]);
    }

    /**
     * @Route("/admin/hall/add", name="redaktor_hall_add")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function add(Request $request, EntityManagerInterface $entityManager, SeatsManager $seatsManager)
    {
        $hall = new Hall();
        $form = $this->createForm(HallType::class, $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hall);
            $entityManager->flush();
            $seatsManager->resizeHall($hall);
            $this->addFlash('success', 'Sál byl přidán.');
            return new RedirectResponse($this->generateUrl('redaktor_hall_list'));
        }

        return $this->render('redaktor/hall/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/hall/{id}/remove", name="redaktor_hall_remove")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function remove(Hall $hall, EntityManagerInterface $entityManager, HallRepository $hallRepository)
    {
        if ($hallRepository->existOrderToHall($hall)) {
            $this->addFlash('danger', 'K tomuto sálu existuje rezervace. Smažte rezervace k tomuto sálu, nebo vytvořte nový sál.');
            return new RedirectResponse($this->generateUrl('redaktor_hall_edit', ['id' => $hall->getId()]));
        }

        try {
            $entityManager->remove($hall);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Sál nejde smazat, protože v tomto sále se odehrávají některá představení. Nejprve odstraňte tato představení.');
            return new RedirectResponse($this->generateUrl('redaktor_hall_edit', ['id' => $hall->getId()]));
        }
        $this->addFlash('success', 'Sál byl odstraněn.');
        return new RedirectResponse($this->generateUrl('redaktor_hall_list'));
    }

    /**
     * @Route("/admin/hall/{id}", name="redaktor_hall_edit")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function edit(
        Hall $hall,
        Request $request,
        EntityManagerInterface $entityManager,
        HallRepository $hallRepository,
        SeatsManager $seatsManager
    )
    {
        $form = $this->createForm(HallType::class, $hall);
        $form->handleRequest($request);
        $rows = $hall->getRowCount();
        $seats = $hall->getRowCount();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hallRepository->existOrderToHall($hall) && ($hall->getRowCount() < $rows || $hall->getSeatsInRow() < $seats)) {
                $this->addFlash('danger', 'Nemůžete zmenšovat sál, pokud existují rezervace k tomuto sálu. Nejdříve smažte rezervace, nebo vytvořte nový sál.');
                return new RedirectResponse($request->getUri());
            }
            $entityManager->flush();

            $seatsManager->resizeHall($hall);
            $this->addFlash('success', 'Sál byl upraven.');
            return new RedirectResponse($this->generateUrl('redaktor_hall_edit', ['id' => $hall->getId()]));
        }

        return $this->render('redaktor/hall/edit.html.twig', [
            'form' => $form->createView(),
            'hall' => $hall,
        ]);
    }
}
