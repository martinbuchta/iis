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
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
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

    /**
     * @Route("/admin/play/{id}-remove-img", name="redaktor_play_remove_image")
     */
    public function removeImage(Play $play, FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {
        if (strlen($play->getImage()) < 1) {
            return new BadRequestHttpException();
        }

        $fileUploader->removeImage($play->getImage());
        $play->setImage("");
        $entityManager->flush();
        $this->addFlash('success', 'Obrázek byl odstraněn.');
        return new RedirectResponse($this->generateUrl('redaktor_play_edit', ['id' => $play->getId()]));
    }

    /**
     * @Route("/admin/play/{id}-remove", name="redaktor_play_remove")
     */
    public function remove(Play $play, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $filename = $play->getImage();

        try {
            $entityManager->remove($play);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Inscenace nejde odstranit, protože má (alespoň jedno) své představení. Odstraňte nejprve tyto představení a zkuste to znovu.');
            return new RedirectResponse($this->generateUrl('redaktor_play_edit', ['id' => $play->getId()]));
        }

        if (strlen($filename) > 0) {
            $fileUploader->removeImage($filename);
        }

        $this->addFlash('success', 'Inscenace byla smazána.');
        return new RedirectResponse($this->generateUrl('redaktor_play_list'));
    }

    /**
     * @Route("/admin/play/{id}", name="redaktor_play_edit")
     * @IsGranted("ROLE_REDAKTOR")
     */
    public function edit(Play $play, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image) {
                if (strlen($play->getImage()) > 0) {
                    $fileUploader->removeImage($play->getImage());
                }
                $imageFileName = $fileUploader->uploadFile($image);
                $play->setImage($imageFileName);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Inscenace byla upravena.');
            return new RedirectResponse($this->generateUrl('redaktor_play_edit', ['id' => $play->getId()]));
        }

        return $this->render('redaktor/play/edit.html.twig', [
            'play' => $play,
            'form' => $form->createView(),
        ]);
    }
}
