<?php

namespace App\Controller;

use App\Repository\PerformanceRepository;
use App\Security\SecurityVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function default(PerformanceRepository $performanceRepository)
    {
        $performances = $performanceRepository->findAllFutures();

        return $this->render('homepage.html.twig', [
            'performances' => $performances,
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(SecurityVoter $securityVoter)
    {
        $securityVoter->onlyRedaktorOrPokladni();

        return $this->render('admin_dashboard.html.twig', [

        ]);
    }
}
