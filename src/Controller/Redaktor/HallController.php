<?php

namespace App\Controller\Redaktor;

use App\Entity\Hall;
use App\Form\HallType;
use App\Repository\HallRepository;
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
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $hall = new Hall();
        $form = $this->createForm(HallType::class, $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hall);
            $entityManager->flush();
            $this->addFlash('success', 'Sál byl přidán.');
            return new RedirectResponse($this->generateUrl('redaktor_hall_list'));
        }

        return $this->render('redaktor/hall/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/hall/{id}", name="redaktor_hall_edit")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function edit(Hall $hall, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(HallType::class, $hall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Sál byl upraven.');
            return new RedirectResponse($this->generateUrl('redaktor_hall_list'));
        }

        return $this->render('redaktor/hall/edit.html.twig', [
            'form' => $form->createView(),
            'hall' => $hall,
        ]);
    }
}
