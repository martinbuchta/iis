<?php

namespace App\Controller\Redaktor;

use App\Entity\Category;
use App\Entity\Genre;
use App\Entity\Play;
use App\Form\PlayType;
use App\Repository\PlayRepository;
use App\Utils\FileUploader;
use App\Utils\Play\PlayManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function add(Request $request, PlayManager $playManager, FileUploader $fileUploader)
    {
        $play = new Play();
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image) {
                $imageFileName = $fileUploader->uploadFile($image);
                $play->setImage($imageFileName);
            }

            $playManager->add($play);
            $this->addFlash('success', 'Inscenace byla přidána.');
            return new RedirectResponse($this->generateUrl('redaktor_play_list'));
        }

        return $this->render('redaktor/play/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/play/add-category", name="redaktor_play_category_add")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function addCategory(Request $request, EntityManagerInterface $entityManager)
    {
        $name = $request->get('name');
        $category = new Category();
        $category->setName($name);

        try {
            $entityManager->persist($category);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse(["id" => $category->getId(), "name" => $category->getName()]);
    }

    /**
     * @Route("/admin/play/add-genre", name="redaktor_play_genre_add")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function addGenre(Request $request, EntityManagerInterface $entityManager)
    {
        $name = $request->get('name');
        $genre = new Genre();
        $genre->setName($name);

        try {
            $entityManager->persist($genre);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse(["id" => $genre->getId(), "name" => $genre->getName()]);
    }
}
