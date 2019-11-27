<?php

namespace App\Controller\Redaktor;

use App\Repository\HallRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HallController extends AbstractController
{
    /**
     * @Route("/admin/hall", name="redaktor_hall_list")
     */
    public function list(HallRepository $hallRepository)
    {
        $halls = $hallRepository->findAll();

        return $this->render('redaktor/hall/list.html.twig', [
            'halls' => $halls,
        ]);
    }
}
