<?php

namespace App\Controller\Redaktor;

use App\Entity\Play;
use App\Form\PlayType;
use App\Repository\PlayRepository;
use App\Utils\Play\PlayManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    /**
     * @Route("/admin/play", name="redaktor_play_list")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function list(PlayRepository $playRepository)
    {
        $plays = $playRepository->findAll();

        return $this->render('redaktor/play/list.html.twig', [
            'plays' => $plays,
        ]);
    }

    /**
     * @Route("/admin/play/add", name="redaktor_play_add")
     */
    public function add(Request $request, PlayManager $playManager)
    {
        $play = new Play();
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playManager->add($play);
            $this->addFlash('success', 'Inscenace byla přidána.');
            return new RedirectResponse($this->generateUrl('redaktor_play_list'));
        }

        return $this->render('redaktor/play/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
