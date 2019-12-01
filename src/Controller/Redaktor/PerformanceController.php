<?php

namespace App\Controller\Redaktor;

use App\Entity\Performance;
use App\Form\PerformanceType;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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
        $performances = $performanceRepository->findBy([], ['time' => 'ASC']);

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

    /**
     * @Route("/admin/performance/{id}-remove", name="redaktor_performance_remove")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function remove(Performance $performance, EntityManagerInterface $entityManager)
    {
        try {
            $entityManager->remove($performance);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('Představení nejde odstranit, protože existují rezervace na toto představení. Nejprve prosím odstrańte tyto rezervace.');
            return new RedirectResponse($this->generateUrl('redaktor_performance_edit', ['id' => $performance->getId()]));
        }

        $this->addFlash('success', 'Představení bylo smazáno.');
        return new RedirectResponse($this->generateUrl('redaktor_performance_list'));
    }

    /**
     * @Route("/admin/performance/{id}", name="redaktor_performance_edit")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function edit(Performance $performance, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PerformanceType::class, $performance);
        $hall = $performance->getHall();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hall != $performance->getHall() && count($performance->getTickets()) > 0) {
                $form->get('hall')->addError(new FormError("Nemůžete změnit sál, protože již existuje alespoň jedna rezervace. Nejdříve ji smažte."));

                return $this->render('redaktor/performance/edit.html.twig', [
                    'performance' => $performance,
                    'form' => $form->createView(),
                ]);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Představení bylo upraveno.');
            return new RedirectResponse($this->generateUrl('redaktor_performance_edit', ['id' => $performance->getId()]));
        }

        return $this->render('redaktor/performance/edit.html.twig', [
            'performance' => $performance,
            'form' => $form->createView(),
        ]);
    }
}
