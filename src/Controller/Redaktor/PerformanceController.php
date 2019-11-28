<?php

namespace App\Controller\Redaktor;

use App\Entity\Performance;
use App\Form\PerformanceType;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PerformanceController extends AbstractController
{
    /**
     * @Route("/admin/performance", name="redaktor_performance_list")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function list(PerformanceRepository $performanceRepository)
    {
        $performances = $performanceRepository->findAll();

        return $this->render('redaktor/performance/list.html.twig', [
            'performances' => $performances,
        ]);
    }

    /**
     * @Route("/admin/performance/add", name="redaktor_performance_add")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $performance = new Performance();
        $form = $this->createForm(PerformanceType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($performance);
            $entityManager->flush();
            $this->addFlash('success', 'Představení bylo vytvořeno.');
            return new RedirectResponse($this->generateUrl('redaktor_performance_list'));
        }

        return $this->render('redaktor/performance/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
