<?php

namespace App\Controller\Administrator;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMINISTRATOR")
     * @Route("/admin/users", name="administrator_manage_users")
     */
    public function manage(UserRepository $repository)
    {
        $users = $repository->findAll();

        return $this->render('administrator/user/manage.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMINISTRATOR")
     * @Route("/admin/users/{id}", name="administrator_manage_user")
     */
    public function manageUser(User $user, Request $request, EntityManagerInterface $entityManager)
    {
        $editForm = $this->createForm(UserAdminType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Uživatel byl upraven.');
            return new RedirectResponse($this->generateUrl('administrator_manage_user', ['id' => $user->getId()]));
        }

        return $this->render('administrator/user/edit.html.twig', [
            'user' => $user,
            'editForm' => $editForm->createView(),
        ]);
    }
}
